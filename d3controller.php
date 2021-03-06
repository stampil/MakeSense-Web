<?php
ini_set('display_errors', 'On');
include_once "datamanager.php";
header('Content-Type: application/javascript');
header("Access-Control-Allow-Origin: *");

//ERROR CODES
$duplicateEntryError = 23000;
$userValidCode = 1000;
$userInvalidCode = 1001;
$badRequest = 0;

//REQUEST CODES
$authenticationCode = "1";
$getSensorNetworkData = "10";



$dataManager = new DataManager();


$requestCode = $_GET['request'];

if ($requestCode==$authenticationCode){
	$username = $_GET['username'];
	$id = $_GET['apiId'];
	$userValid = $dataManager->validateApiUser($username, $id);
	if ($userValid){
		echo $userValidCode;
	}
	else{
		echo $userInvalidCode;
	}
}

else if ($requestCode==$getSensorNetworkData){
	$username = $_GET['username'];
	$id = $_GET['id'];
	$userValid = $dataManager->validateApiUser($username, $id);

	if ($userValid){
		$netId = $_GET['netId'];
		$sensorData = array();
		try {
			$sensors = $dataManager->getSensorsInNetwork($netId);
			$sensorIds = array();
			foreach($sensors as $sensor){
				array_push($sensorIds, $sensor[0]);
			}
			$sensorData = $dataManager->getLatestReadingsForSensors($sensorIds);
		}
		catch(PDOException $e){
			echo $badRequest;
		}
		echo json_encode($sensorData);
	}
	else{
		echo $userInvalidCode;
	}
}



else
{
	echo $badRequest;
}
?>