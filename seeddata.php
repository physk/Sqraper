<?php
date_default_timezone_set("America/New_York");
$timeStarted  = strtotime(date('m/d/Y h:i:s a', time()));

function displayError($errDescription) {	
	echo "\n\e[1;31m>>>\e[0m ========================================\n";
	echo "\e[1;31m>>>\e[0m ERROR: $errDescription\n";			
	echo "\e[1;31m>>>\e[0m SLEEP: 5 seconds.\n";			
	echo "\e[1;31m>>>\e[0m ========================================\n\n";		
	sleep(5);	
}

function getConfig() {
	if (!file_exists('sqraper_config.json')) {
		echo "\e[1;31mCREATE FILE:\e[0m sqraper_config.json did not exist. Creating and reading default configuration JSON file.\n";
		$defaultConfig = array(
			'qTrips' => ['!!mG7VJxZNCI','!!Hs1Jq13jV6'],
			'bogusTrips' => [],
			'boards' => ['projectdcomms','qresearch'],
			'domain8Kun' => '8kun.top',
			'domain8KunForLinks' => '8kun.net',
			'useLoki' => true,
			'lokiKun' => 'http://pijdty5otm38tdex6kkh51dkbkegf31dqgryryz3s3tys8wdegxo.loki',
			'useTor' => false,
			'torKun' => 'http://www.jthnx5wyvjvzsxtu.onion',
			'saveRemoteFilesToLocal' => true,
			'readFromLocal8KunFiles' => false,
			'sleepBetweenNewQPostChecks' => 150,
			'offPeakSleepBetweenNewQPostChecks' => 300,
			'maxDownloadAttempts' => 10,
			'pauseBetweenDownloadAttempts' => 1,			
			'productionPostsJSONFilename' => 'posts.json',
			'productionJSONFolder' => 'json/',
			'productionMediaFolder' => 'media/',
			'productionMediaURL' => 'https://yourserver.com/media/', // If not blank, the media URL in the file will be build with this domain and path.
			'ftpServers' => [],
			'useColors' => true
		);		
		array_push($defaultConfig[ftpServers], array('protocol' => 'ftp','server' => 'ftp.yourserver.com','loginId' => 'your_user_name', 'password' => 'your_password', 'uploadJSON' => false, 'uploadMedia' => false, 'jsonFolder' => '/data/json/', 'mediaFolder' => '/media/', 'useCurl' => false));
		array_push($defaultConfig[ftpServers], array('protocol' => 'ftp','server' => 'ftp.yourserver2.com','loginId' => 'your_user_name2', 'password' => 'your_password2', 'uploadJSON' => false, 'uploadMedia' => false, 'jsonFolder' => '/data/json/', 'mediaFolder' => '/media/', 'useCurl' => false));
		
		$GLOBALS['qTrips'] = $defaultConfig['qTrips'];
		$GLOBALS['bogusTrips'] = $defaultConfig['bogusTrips'];
		$GLOBALS['boards'] = $defaultConfig['boards'];
		$GLOBALS['domain8Kun'] = $defaultConfig['domain8Kun'];
		$GLOBALS['domain8KunForLinks'] = $defaultConfig['domain8KunForLinks'];
		$GLOBALS['lokiKun'] = $defaultConfig['lokiKun'];
		$GLOBALS['torKun'] = $defaultConfig['torKun'];
		$GLOBALS['useLoki'] = $defaultConfig['useLoki'];
		$GLOBALS['useTor'] = $defaultConfig['useTor'];
		$GLOBALS['saveRemoteFilesToLocal'] = $defaultConfig['saveRemoteFilesToLocal'];
		$GLOBALS['readFromLocal8KunFiles'] = $defaultConfig['readFromLocal8KunFiles'];
		$GLOBALS['sleepBetweenNewQPostChecks'] = $defaultConfig['sleepBetweenNewQPostChecks'];
		$GLOBALS['offPeakSleepBetweenNewQPostChecks'] = $defaultConfig['offPeakSleepBetweenNewQPostChecks'];		
		$GLOBALS['maxDownloadAttempts'] = $defaultConfig['maxDownloadAttempts'];		
		$GLOBALS['pauseBetweenDownloadAttempts'] = $defaultConfig['pauseBetweenDownloadAttempts'];		
		$GLOBALS['productionPostsJSONFilename'] = $defaultConfig['productionPostsJSONFilename'];
		$GLOBALS['productionMediaFolder'] = $defaultConfig['productionMediaFolder'];
		$GLOBALS['productionMediaURL'] = $defaultConfig['productionMediaURL'];
		$GLOBALS['productionJSONFolder'] = $defaultConfig['productionJSONFolder'];
		$GLOBALS['ftpServers'] = $defaultConfig['ftpServers'];		
		$GLOBALS['useColors'] = $defaultConfig['useColors'];
		file_put_contents('sqraper_config.json', json_encode($defaultConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK), LOCK_EX);
	} else {		
		echo "\e[1;32mREAD CONFIG:\e[0m sqraper_config.json.\n";		
		$currentConfig = @file_get_contents('sqraper_config.json');	
		if (!$currentConfig) {		
			displayError("getConfig unable to read file contents. Halting.");
			exit;
		} else {
			$currentConfigJSON = json_decode($currentConfig, true);
			if ($currentConfigJSON == FALSE) {
				displayError("getConfig unable to parse JSON. Halting.");
				exit;
			} else {


				$GLOBALS['qTrips'] = $currentConfigJSON['qTrips'];
				$GLOBALS['bogusTrips'] = $currentConfigJSON['bogusTrips'];
				$GLOBALS['boards'] = $currentConfigJSON['boards'];
				$GLOBALS['domain8Kun'] = $currentConfigJSON['domain8Kun'];
				$GLOBALS['domain8KunForLinks'] = $currentConfigJSON['domain8KunForLinks'];
				$GLOBALS['lokiKun'] = $currentConfigJSON['lokiKun'];
				$GLOBALS['torKun'] = $currentConfigJSON['torKun'];
				$GLOBALS['useLoki'] = $currentConfigJSON['useLoki'];
				$GLOBALS['useTor'] = $currentConfigJSON['useTor'];
				$GLOBALS['saveRemoteFilesToLocal'] = $currentConfigJSON['saveRemoteFilesToLocal'];
				$GLOBALS['readFromLocal8KunFiles'] = $currentConfigJSON['readFromLocal8KunFiles'];
				$GLOBALS['sleepBetweenNewQPostChecks'] = $currentConfigJSON['sleepBetweenNewQPostChecks'];
				$GLOBALS['offPeakSleepBetweenNewQPostChecks'] = $currentConfigJSON['offPeakSleepBetweenNewQPostChecks'];

				if (!isset($currentConfigJSON['maxDownloadAttempts'])) {
					$GLOBALS['maxDownloadAttempts'] = 10;
				} else {
					$GLOBALS['maxDownloadAttempts'] = $currentConfigJSON['maxDownloadAttempts'];					
				}

				if (!isset($currentConfigJSON['pauseBetweenDownloadAttempts'])) {
					$GLOBALS['pauseBetweenDownloadAttempts'] = 1;				
				} else {
					$GLOBALS['pauseBetweenDownloadAttempts'] = $currentConfigJSON['pauseBetweenDownloadAttempts'];					
				}
				
				$GLOBALS['productionPostsJSONFilename'] = $currentConfigJSON['productionPostsJSONFilename'];	
				$GLOBALS['productionMediaFolder'] = $currentConfigJSON['productionMediaFolder'];
				$GLOBALS['productionMediaURL'] = $currentConfigJSON['productionMediaURL'];
				$GLOBALS['productionJSONFolder'] = $currentConfigJSON['productionJSONFolder'];								

				if (!isset($currentConfigJSON['ftpServers'])) {
					$GLOBALS['ftpServers'] = [];
				} else {
					$GLOBALS['ftpServers'] = $currentConfigJSON['ftpServers'];
				}

				if (!isset($currentConfigJSON['useColors'])) {
					$GLOBALS['useColors'] = true;					
				} else {
					$GLOBALS['useColors'] = $currentConfigJSON['useColors'];					
				}

			}
		}
	}		
}

getConfig();

/* ================================================ */
/* = Download and save the latest posts.json file = */
/* ================================================ */

$jsonUrl = "https://qalerts.app/data/json/posts.json";
//$jsonUrl = "https://qanon.pub/data/json/posts.json";

/*
https://qanon.pub/data/json/posts.json
https://keybase.pub/qntmpkts/data/json/posts.json
https://qalerts.app/data/json/posts.json
*/

if ((isset($productionJSONFolder)) && ($productionJSONFolder !== '')) {
	if (!file_exists($productionJSONFolder)) {
		echo "\e[1;31mCREATE FOLDER:\e[0m " . $productionJSONFolder . "\n";
		mkdir($productionJSONFolder, 0777, true);
	}
}
echo "\e[1;32mDOWNLOAD JSON:\e[0m " . $jsonUrl . " > " . $productionJSONFolder . "posts.json" . "\n";
$thisMedia = @file_get_contents($jsonUrl);
if ($thisMedia) {
	file_put_contents($productionJSONFolder . "posts.json", $thisMedia, LOCK_EX);	
}	

/* ================================================ */


/* ===============================================+= */
/* = Download and save the latest media collection = */
/* ===============================================+= */

if ((isset($productionMediaFolder)) && ($productionMediaFolder !== '')) {

	$downloadedImagesCount = 0;
	$mediaUrl = "https://qalerts.net/media/";
	$thisMedia = @file_get_contents($mediaUrl);
	if (!file_exists($productionMediaFolder)) {
		echo "\e[1;31mCREATE FOLDER:\e[0m " . $productionMediaFolder . "\n";
		mkdir($productionMediaFolder, 0777, true);
	}
	$jsonMedia = @json_decode($thisMedia, true);
	if (($jsonMedia == FALSE) && (json_last_error() !== JSON_ERROR_NONE)) {
		displayError("JSON parse error");
	} else {					
		if (!empty($jsonMedia)) {
			foreach($jsonMedia as $media) {					
				if (!file_exists($productionMediaFolder . basename($media))) {
					echo "\e[1;32mDOWNLOAD MEDIA:\e[0m " . $productionMediaFolder . basename($media) . "\n";
					$thisFile = @file_get_contents($media);
					file_put_contents($productionMediaFolder . basename($media), $thisFile, LOCK_EX);	
					$downloadedImagesCount ++;
				} else {
					echo "\e[1;33mSKIP EXISTING MEDIA:\e[0m " . $productionMediaFolder . basename($media) . "\n";			
				}
			}
		}
		unset($jsonMedia);		
	}

} else {
	
	echo "\e[1;33mSKIP DOWNLOAD MEDIA:\e[0m productionMediaFolder is configured as blank." . "\n";			

}

/* ===============================================+= */

$timeFinished  = strtotime(date('m/d/Y h:i:s a', time()));
$differenceInSeconds = $timeFinished - $timeStarted;

echo "\e[1;32mFINISHED:\e[0m " . date("m/d/Y h:i:sa") . ". Took $differenceInSeconds second(s) to complete. $downloadedImagesCount image(s) downloaded.\n";
?>
