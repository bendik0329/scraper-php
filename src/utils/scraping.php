<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use voku\helper\HtmlDomParser;

function scrapeShopPage($paginationNumber)
{
  $productDataList = array();

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "https://scrapeme.live/shop/page/$paginationNumber");
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $pageHtml = curl_exec($curl);
  curl_close($curl);

  $paginationHtmlDomParser = HtmlDomParser::str_get_html($pageHtml);

  // retrieving the list of products on the page
  $productElements = $paginationHtmlDomParser->find("li.product");

  foreach ($productElements as $productElement) {
    $productDataList[] = scrapeProduct($productElement);
  }

  return $productDataList;
}

function scrapeProduct($productElement)
{
  // extracting the product data
  $url = $productElement->findOne("a")->getAttribute("href");
  $image = $productElement->findOne("img")->getAttribute("src");
  $name = $productElement->findOne("h2")->text;
  $price = $productElement->findOne(".price span")->text;

  // transforming the product data in an associative array
  return array(
    "url" => $url,
    "image" => $image,
    "name" => $name,
    "price" => $price
  );
}

function scrateForeclosure()
{
  $dataList = array();

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
  $pageHtml = curl_exec($curl);
  curl_close($curl);

  $paginationHtmlDomParser = HtmlDomParser::str_get_html($pageHtml);

  $propertyList = $paginationHtmlDomParser->findOne("#search-page-list-container .result-list-container ul.photo-cards");

  foreach($propertyList->childNodes as $propertyItem) {
    $dataList[] = scrateItem($propertyItem->findOne("article.property-card div"));
  }

  return $dataList;
}

function scrateItem($element)
{
  // get url
  $swipeFirstElement = $element->findone("#swipeable")->firstChild();
  $url = $swipeFirstElement->findOne("a")->getAttribute("href");

  // get image list
  $imgList = [];
  $swipeElements = $element->find("#swipeable div");
  foreach($swipeElements as $swipeElement) {
    $imgList[] = $swipeElement->findOne("a div picture img")->getAttribute("src");
  }

  return array(
    "url" => $url,
    "images" => $imgList,
  );

  // $url = $element->findOne('//*[@id="swipeable"]/div[1]/a')->getAttribute("href");
  // $bed = $element->findOne('//*[@id="zpid_17500338"]/div/div[1]/div[3]/ul/li[1]/b')->text;
  // $bath = $element->findOne('//*[@id="zpid_17500338"]/div/div[1]/div[3]/ul/li[2]/b')->text;
  // $sqft = $element->findOne('//*[@id="zpid_17500338"]/div/div[1]/div[3]/ul/li[3]/b')->text;
  // $price = $element->findOne('//*[@id="zpid_21109934"]/div/div[1]/div[2]/div/span')->text;
  // $address = $element->findOne('//*[@id="zpid_17500338"]/div/div[1]/a/address')->text;

  // // transforming the product data in an associative array
  // return array(
  //   "url" => $url,
  //   "bed" => $bed,
  //   "bath" => $bath,
  //   "sqft" => $sqft,
  //   "price" => $price,
  //   "address" => $address,
  // );
}
