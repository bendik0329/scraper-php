<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';
require_once  __DIR__ . '/utils/scraping.php';

use voku\helper\HtmlDomParser;

// initializing the cURL request
$curl = curl_init();
// setting the URL to reach with a GET HTTP request
curl_setopt($curl, CURLOPT_URL, 'https://www.zillow.com/homes/for_sale/fore_lt/?searchQueryState=%7B%22mapBounds%22%3A%7B%22north%22%3A42.009517%2C%22east%22%3A-114.131253%2C%22south%22%3A32.528832%2C%22west%22%3A-124.482045%7D%2C%22mapZoom%22%3A6%2C%22isMapVisible%22%3Atrue%2C%22filterState%22%3A%7B%22ah%22%3A%7B%22value%22%3Atrue%7D%2C%22sort%22%3A%7B%22value%22%3A%22globalrelevanceex%22%7D%2C%22auc%22%3A%7B%22value%22%3Afalse%7D%2C%22nc%22%3A%7B%22value%22%3Afalse%7D%2C%22fsbo%22%3A%7B%22value%22%3Afalse%7D%2C%22cmsn%22%3A%7B%22value%22%3Afalse%7D%2C%22fsba%22%3A%7B%22value%22%3Afalse%7D%2C%22sche%22%3A%7B%22value%22%3Afalse%7D%2C%22schm%22%3A%7B%22value%22%3Afalse%7D%2C%22schh%22%3A%7B%22value%22%3Afalse%7D%2C%22schp%22%3A%7B%22value%22%3Afalse%7D%2C%22schr%22%3A%7B%22value%22%3Afalse%7D%2C%22schc%22%3A%7B%22value%22%3Afalse%7D%2C%22schu%22%3A%7B%22value%22%3Afalse%7D%7D%2C%22isListVisible%22%3Atrue%7D');
// to make the cURL request follow eventual redirects
// and reach the final page of interest
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// to get the data returned by the cURL request as a string
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// setting the User-Agent header
curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
// executing the cURL request and
// get the HTML of the page as a string
$html = curl_exec($curl);
// releasing the cURL resources
curl_close($curl);

print_r($html);exit();
// initializing HtmlDomParser
$htmlDomParser = HtmlDomParser::str_get_html($html);

// retrieving the HTML pagination elements with
// the ".page-numbers a" CSS selector
$paginationElements = $htmlDomParser->find(".page-numbers a");
$paginationLinks = [];
foreach ($paginationElements as $paginationElement) {
    // populating the paginationLinks set with the URL
    // extracted from the href attribute of HTML pagination element
    $paginationLink = $paginationElement->getAttribute("href");
    // avoiding duplicates in the list of URLs
    if (!in_array($paginationLink, $paginationLinks)) {
        $paginationLinks[] = $paginationLink;
    }
}

// removing all non-numeric characters in the last element of
// the $paginationLinks array to retrieve the highest pagination number
$highestPaginationNumber = preg_replace("/\D/", '', end($paginationLinks));

$productDataList = array();
// iterate over all "/shop/pages/X" pages and retrieve all product data
for ($paginationNumber = 1; $paginationNumber <= $highestPaginationNumber; $paginationNumber++) {
    $productDataList = array_merge($productDataList, scrapeShopPage($paginationNumber));
}

echo json_encode($productDataList);

// writing the data scraped to a database/file