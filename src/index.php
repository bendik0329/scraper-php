<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';

use voku\helper\HtmlDomParser;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

$host = 'http://localhost:4444/wd/hub';

$capabilities = Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
$driver = Facebook\WebDriver\Remote\RemoteWebDriver::create($host, $capabilities);