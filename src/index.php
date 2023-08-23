<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';

use voku\helper\HtmlDomParser;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverCapabilityType;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriverService;

$host = 'http://localhost:4444/wd/hub';

// Set the path to the ChromeDriver executable
$driverService = ChromeDriverService::start(['executable' => '/../webdriver/chromedriver.exe']);

$capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'chrome');
$driver = RemoteWebDriver::create($host, $capabilities);

// Your test code here...

$driver->quit();
$driverService->stop();
