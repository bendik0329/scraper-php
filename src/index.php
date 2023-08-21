<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';
require_once  __DIR__ . '/utils/scraping.php';

use voku\helper\HtmlDomParser;

// foreach (STATE_LIST as $key => $value) {
//   $state = strtolower($key);
  
//   $filterState = array(
//     "fsba" => ["value" => false],
//     "fsbo" => ["value" => false],
//     "nc" => ["value" => false],
//     "cmsn" => ["value" => false],
//     "auc" => ["value" => false],
//     "sort" => ["value" => "globalrelevanceex"],
//     "beds" => ["min" => 1],
//     "baths" => ["min" => 1],
//     "sqft" => ["max" => 500],
//   );
//   $query = array(
//     "isMapVisible"=> true,
//     "filterState" => $filterState,
//     "isListVisible"=> true,
//     "mapZoom"=> 6,
//     "usersSearchTerm"=> "CA"
//   );
//   $queryString = json_encode($query);
//   $searchQueryState = urlencode($queryString);

//   $url = "https://www.zillow.com/$state/foreclosures/?searchQueryState=$searchQueryState";
//   print_r($url . "\n");
// }
// exit();
$url = 'https://www.zillow.com/wy/foreclosures/';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_PROXY, 'http://60cb4030eb5826662d404f6cb6bb10040a3f775a:@proxy.zenrows.com:8001');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'Referrer: https://www.google.com',
]);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$html = curl_exec($curl);
curl_close($curl);

$htmlDomParser = HtmlDomParser::str_get_html($html);

$resultCountText = $htmlDomParser->findOne(".search-page-list-header .search-subtitle span.result-count")->text;
$resultCount = 0;
if (preg_match('/(\d+)/', $resultCountText, $matches)) {
    $resultCount = $matches[1];
}

$propertyCountPerPage = $htmlDomParser->findOne("#search-page-list-container .result-list-container ul.photo-cards")->childNodes->length;
print_r($resultCount);
print_r($propertyCountPerPage);
exit();

if ($resultCount <= $propertyCountPerPage) {
  print_r("Hey bro");
}
exit();

$i = 0;
foreach($propertyList as $propertyItem) {
  print_r("index:" . $i);
  $i++;
}
exit();

$propertyListArray = get_object_vars($propertyList);

if ($resultCount <= count($propertyListArray)) {

}
print_r($propertyListArray);
exit();

// $i = 0;
// foreach($propertyList as $propertyItem) {
//   print_r("index:" . $i);
//   $i++;
// }
// exit();
// $highestPaginationNumber = preg_replace("/\D/", '', end($count));

// print_r($highestPaginationNumber);
// exit();
// $result = array();
// $result = array_merge($result, scrateForeclosure());

// echo json_encode($result);
// exit();