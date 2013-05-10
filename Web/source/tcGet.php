<?php
$mysqli = new mysqli('localhost','vericon','18450be');

function CheckAccess()
{
	if (ip2long($_SERVER['REMOTE_ADDR']) != ip2long("122.129.217.194")) {
		return false;
	} else {
		return true;
	}
}

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if ($referer_check[1] != $referer || !CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

$token = $_COOKIE['vc_token'];

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

if ($q->num_rows == 0)
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$q->free();

header('Content-type: application/json');

$method = $_POST["method"];
$username = "smb_tc_trial";
$password = "Password01";

if ($method == "search")
{
	$input = $_POST["input"];
		
	if($input == ""){die('[]');}

	$client = new SoapClient('https://stage.totalcheck.sensis.com.au/service/enhanced?wsdl', array('trace' => 1));
	$auth = '<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	<wsse:UsernameToken>
	<wsse:Username>'.$username.'</wsse:Username>
	<wsse:Password>'.$password.'</wsse:Password>
	</wsse:UsernameToken>
	</wsse:Security>';
	$authvalues = new SoapVar($auth,XSD_ANYXML);
	$header = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd", "Security", $authvalues, true);
	$client->__setSoapHeaders($header);
	$da = $client->suggestAddresses( array('search' => array(
												'formattedAddress' => $input,
												'formattedAddressIncludesPostcode' => false,
												'formattedAddressIncludesState' => true,
												'formattedAddressIncludesSuburb' => true,
												'searchType' => 'Both'
												)
											)
										);
	
	if ($da->return->resultStatus == 1)
	{
		$results = array(
			'list' => $da->return->resultList
		);
		
		if (count($results['list']) == 1)
		{
			$data = array(
				'id' => $results['list']->index,
				'label' => preg_replace('!\s+!', ' ', $results['list']->formattedAddress),
				'value' => preg_replace('!\s+!', ' ', $results['list']->formattedAddress),
				'postal' => $results['list']->postal,
				'whitepages' => $results['list']->whitePages,
				'search' => $results['list']->search->formattedAddress,
				'formattedAddress' => ''
			);
			echo "[" . json_encode($data) . "]";
		}
		else
		{
			$data = array();
			foreach ($results['list'] as $row)
			{
				$data_push = array(
					'id' => $row->index,
					'label' => preg_replace('!\s+!', ' ', $row->formattedAddress),
					'value' => preg_replace('!\s+!', ' ', $row->formattedAddress),
					'postal' => $row->postal,
					'whitepages' => $row->whitePages,
					'search' => $row->search->formattedAddress,
					'formattedAddress' => ''
				);
				array_push($data, $data_push);
			}
			echo json_encode($data);
		}
	}
	else
	{
		echo '[]';
	}
}
elseif ($method == "select")
{
	$input = $_POST["input"];
	$index = $_POST["index"];
	$postal = $_POST["postal"];
	$whitepages = $_POST["whitepages"];
	
	if($input == "" || $index == ""){die('');}
	
	$client = new SoapClient('https://stage.totalcheck.sensis.com.au/service/enhanced?wsdl', array('trace' => 1, 'exceptions' => 0));
	$auth = '<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	<wsse:UsernameToken>
	<wsse:Username>'.$username.'</wsse:Username>
	<wsse:Password>'.$password.'</wsse:Password>
	</wsse:UsernameToken>
	</wsse:Security>';
	$authvalues = new SoapVar($auth,XSD_ANYXML);
	$header = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd", "Security", $authvalues, true);
	$client->__setSoapHeaders($header);
	
	$da = $client->selectAddress( array('suggestion' => array(
									'formattedAddress' => '',
									'index' => $index,
									'postal' => $postal,
									'search' => array(
											'formattedAddress' => $input,
											'formattedAddressIncludesPostcode' => true,
											'formattedAddressIncludesState' => true,
											'formattedAddressIncludesSuburb' => true,
											'name' => '',
											'phoneNumber' => '',
											'postcode' => '',
											'searchType' => '',
											'state' => '',
											'streetName' => '',
											'streetNumber' => '',
											'streetType' => '',
											'suburb' => ''
									),
									'secondaryName' => '',
									'whitePages' => $whitepages
								)
							)
						);

	$results = array(
		'list' => $da->return->detailList
	);
	
	if (count($results['list']) < 1)
	{
		$data = array(
			'dpid' => $da->return->dpid,
			'barcode' => $da->return->barcode,
			'formattedAddress' => preg_replace('!\s+!', ' ', $da->return->formattedAddress),
			'buildingName' => $da->return->buildingName,
			'subPremise' => $da->return->subPremise,
			'streetNumber' => $da->return->streetNumber,
			'streetName' => $da->return->streetName,
			'streetType' => $da->return->streetType,
			'streetSuffix' => $da->return->streetSuffix,
			'suburb' => $da->return->suburb,
			'state' => $da->return->state,
			'postcode' => $da->return->postcode
		);
	}
	else
	{
		$data = array();
		
		$data_push = array(
			'label' => preg_replace('!\s+!', ' ', $da->return->formattedAddress),
			'value' => preg_replace('!\s+!', ' ', $da->return->formattedAddress),
			'dpid' => $da->return->dpid,
			'barcode' => $da->return->barcode,
			'formattedAddress' => preg_replace('!\s+!', ' ', $da->return->formattedAddress),
			'buildingName' => $da->return->buildingName,
			'subPremise' => $da->return->subPremise,
			'streetNumber' => $da->return->streetNumber,
			'streetName' => $da->return->streetName,
			'streetType' => $da->return->streetType,
			'streetSuffix' => $da->return->streetSuffix,
			'suburb' => $da->return->suburb,
			'state' => $da->return->state,
			'postcode' => $da->return->postcode
		);
		
		array_push($data, $data_push);
		
		foreach ($results['list'] as $row)
		{
			$data_push = array(
				'label' => preg_replace('!\s+!', ' ', $row->formattedAddress),
				'value' => preg_replace('!\s+!', ' ', $row->formattedAddress),
				'dpid' => $row->dpid,
				'barcode' => $row->barcode,
				'formattedAddress' => preg_replace('!\s+!', ' ', $row->formattedAddress),
				'buildingName' => $row->buildingName,
				'subPremise' => $row->subPremise,
				'streetNumber' => $row->streetNumber,
				'streetName' => $row->streetName,
				'streetType' => $row->streetType,
				'streetSuffix' => $row->streetSuffix,
				'suburb' => $row->suburb,
				'state' => $row->state,
				'postcode' => $row->postcode
			);
			array_push($data, $data_push);
		}
	}
	
	echo json_encode($data);
}
elseif ($method == "street_type")
{
	$input = $_POST["input"];
	
	$q = $mysqli->query("SELECT * FROM `aut`.`street_type` WHERE `code` LIKE '" . $mysqli->real_escape_string($input) . "%' OR `name` LIKE '" . $mysqli->real_escape_string($input) . "%'") or die($mysqli->error);
	while ($data = $q->fetch_assoc())
	{
		$d[] = "{ \"id\": \"" . $data["code"] . "\", \"label\": \"" . $data["name"] . "\" }";
	}
	$q->free();
	
	echo "[" . implode(", ",$d) . "]";
}
elseif ($method == "street_type_suffix")
{
	$input = $_POST["input"];
	
	$q = $mysqli->query("SELECT * FROM `aut`.`street_type_suffix` WHERE `code` LIKE '" . $mysqli->real_escape_string($input) . "%' OR `name` LIKE '" . $mysqli->real_escape_string($input) . "%'") or die($mysqli->error);
	while ($data = $q->fetch_assoc())
	{
		$d[] = "{ \"id\": \"" . $data["code"] . "\", \"value\": \"" . $data["code"] . "\", \"label\": \"" . $data["name"] . "\" }";
	}
	$q->free();
	
	echo "[" . implode(", ",$d) . "]";
}
elseif ($method == "suburb_town")
{
	$input = $_POST["input"];
	
	$q = $mysqli->query("SELECT * FROM `aut`.`locality` WHERE `name` LIKE '%" . $mysqli->real_escape_string($input) . "%'") or die($mysqli->error);
	while ($data = $q->fetch_assoc())
	{
		$d[] = "{ \"id\": \"" . $data["id"] . "\", \"value\": \"" . $data["name"] . "\", \"label\": \"" . $data["name"] . " " . $data["state"] . " " . $data["postcode"] . "\", \"suburb_town\": \"" . $data["name"] . "\", \"state\": \"" . $data["state"] . "\", \"postcode\": \"" . $data["postcode"] . "\" }";
	}
	$q->free();
	
	echo "[" . implode(", ",$d) . "]";
}

$mysqli->close();
?>