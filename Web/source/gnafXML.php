<?php
/*$input = $_GET["input"];

if(!$input){die('null');}

$client = new SoapClient('https://ws.callpointspatialgeocoder.com/GeocoderService.svc?wsdl', array('trace' => 1));

$data = $client->Geocode(array('credential' => array('Username' => 'Odai Najjar', 'Password' => 'somoxoge!'), 'options' => array('allowStreetLevelGeocode' => false, 'allowWrongSideOfStreet' => true, 'rangeTolerance' => 2), 'line1' => $input, 'line2' => ''));

$toJSON = array(
	'addressIdentifier'=>$data->GeocodeResult->GeocoderAddress->addressIdentifier
);

echo $toJSON['addressIdentifier'];*/

echo 'null';
?>