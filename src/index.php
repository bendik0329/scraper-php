<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';
// require_once  __DIR__ . '/utils/scraping.php';

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

// sleep(10);

if ($html !== false) {
  $htmlDomParser = HtmlDomParser::str_get_html($html);

  $resultCountText = $htmlDomParser->findOne(".search-page-list-header .search-subtitle span.result-count")->text;
  $resultCount = 0;
  if (preg_match('/(\d+)/', $resultCountText, $matches)) {
    $resultCount = $matches[1];
  }

  print_r($resultCount);
  print_r("\n");

  $result = array();
  $i = 0;

  // if ($propertyCard && $propertyCard->childNodes->length > 0) {
  $propertyElements = $htmlDomParser->find("#grid-search-results > ul > li");
  foreach ($propertyElements as $propertyElement) {
    if (!$propertyElement->getAttribute("data-test")) {
      $swipeElements = $propertyElement->find("div#swipeable > div");
      $url = $swipeElements->findOne("a")->getAttribute("href");
      print_r("url->>" . $url);
      print_r("index->>" . $i);
      print_r("\n");
      $i++;
    }
    
    // $url = $swipeElements->findOne("a")->getAttribute("href");

    // $imgList = [];
    // foreach ($swipeElements as $swipeElement) {
    //   $imgList[] = $swipeElement->findOne("picture img")->getAttribute("src");
    // }

    
    // $swipeElements = $propertyElement->findOne("#swipeable");
    // $url = $swipeElements->firstChild()->findOne("a")->getAttribute("href");

    // $imgList = [];
    // foreach ($swipeElements->childNodes as $swipeElement) {
    //   $imgList[] = $swipeElement->findOne("picture img")->getAttribute("src");
    // }
    // $result[] = scrapeItem($propertyElement);
  }
  // }

  // print_r($result);
  exit();
}


// $propertyElements = $htmlDomParser->find("#grid-search-results > ul > li");

// $propertyCard = $htmlDomParser->findOne("#search-page-list-container .result-list-container ul.photo-cards");

// if ($propertyCard && $propertyCard->childNodes->length > 0) {
//   foreach ($propertyCard->childNodes as $propertyElement) {
//     $swipeElements = $propertyElement->findOne("#swipeable");
//     var_dump($swipeElements);
//     $url = $swipeElements->firstChild()->findOne("a")->getAttribute("href");

//     $imgList = [];
//     foreach($swipeElements->childNodes as $swipeElement) {
//       $imgList[] = $swipeElement->findOne("picture img")->getAttribute("src");
//     }
//     var_dump($swipeElements);
//     var_dump($url);
//     var_dump($imgList);
//     $result[] = scrapeItem($propertyElement);
//   }
// }

// $propertyElements = $htmlDomParser->find("#search-page-list-container .result-list-container ul.photo-cards li.gTOWtl");
// foreach($propertyElements as $propertyElement) {
//   // $result[] = scrapeItem($propertyElement);
//   print_r("index->>" . $i);
//   print_r($propertyElement);
//   print_r("\n");
//   $i++;
// }

function scrapeItem($propertyElement)
{
  // get url
  $swipeFirstElement = $propertyElement->findone("#swipeable")->firstChild();
  $url = $swipeFirstElement->findOne("a")->getAttribute("href");

  // get image list
  $imgList = [];
  $swipeElements = $propertyElement->find("#swipeable div");
  foreach ($swipeElements as $swipeElement) {
    $imgList[] = $swipeElement->findOne("a div picture img")->getAttribute("src");
  }

  return array(
    "url" => $url,
    "images" => $imgList,
  );
}






// $propertyCountPerPage = $htmlDomParser->findOne("#search-page-list-container .result-list-container ul.photo-cards")->childNodes->length;

// $result = array();

// if ($resultCount <= $propertyCountPerPage) {
//   $result = array_merge($result, scrapeForeclosure());
// } else {
//   print_r("die out bro");
// }

// echo json_encode($result);
// exit();
