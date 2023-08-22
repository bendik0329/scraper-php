<?php
require_once('vendor/autoload.php');
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

// start Firefox with 5 seconds timeout
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::firefox();
$driver = Facebook\WebDriver\Remote\RemoteWebDriver::create($host, $capabilities, 5000);

// navigate to 'http://www.google.com'
$driver->get('http://www.google.com');

// adding cookie
$driver->manage()->addCookie([
  'name' => 'cookie_name',
  'value' => 'cookie_value',
]);

// click the link 'About'
$link = $driver->findElement(
  WebDriverBy::linkText('About')
);
$link->click();

// print the title of the current page
echo "The title is '" . $driver->getTitle() . "'\n";

// print the URI of the current page
echo "The current URI is '" . $driver->getCurrentURL() . "'\n";

// search for 'php' in the search box
$input = $driver->findElement(
  WebDriverBy::name('q')
);
$input->sendKeys('php');
$input->submit();

// wait at most 10 seconds until at least one result is shown
$driver->wait()->until(
  Facebook\WebDriver\WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
    WebDriverBy::className('g')
  )
);

// close the Firefox
$driver->quit();
?>