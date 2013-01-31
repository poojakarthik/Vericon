<?PHP
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