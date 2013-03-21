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

if(!$_GET['abn']){die('{"error":"true"}');}

$abr = new SoapClient('http://abr.business.gov.au/abrxmlsearch/AbrXmlSearch.asmx?WSDL');

$data = $abr->ABRSearchByABN(array('searchString'=>$_GET['abn'],'includeHistoricalDetails'=>'N','authenticationGuid'=>'24183d05-6498-4d78-89d3-2bd791c6bc17'));

if ($data->ABRPayloadSearchResults->response->businessEntity->entityStatus->entityStatusCode == null)
{
	echo '{"error":"true"}';
	exit;
}

$toJSON = array(
	'organisationName'=>$data->ABRPayloadSearchResults->response->businessEntity->mainName->organisationName,
	'tradingName'=>$data->ABRPayloadSearchResults->response->businessEntity->mainTradingName->organisationName,
	'entityName'=>$data->ABRPayloadSearchResults->response->businessEntity->legalName->familyName . ", " . $data->ABRPayloadSearchResults->response->businessEntity->legalName->givenName,
	'entityStatus'=>$data->ABRPayloadSearchResults->response->businessEntity->entityStatus->entityStatusCode,
	'entityDescription'=>$data->ABRPayloadSearchResults->response->businessEntity->entityType->entityDescription
);

echo json_encode($toJSON);
?>