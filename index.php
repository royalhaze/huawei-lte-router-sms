<?php
/**
 * GitHub: RoyalHaze
 * Date: 10/4/23
 * Time: 12:56 AM
 **/
require 'vendor/autoload.php';


$text = 'TEXT';
$number = 'NUM';
$password = 'password';
$router_ip = '192.168.1.1';


$driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create('http://127.0.0.1:4444', [
    'platform' => 'ANY',
    'browserName' => 'chrome',
]);

$driver->get("http://$router_ip");

$driver->wait(10)->until(
    \Facebook\WebDriver\WebDriverExpectedCondition::titleContains('4G')
);

$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('login_password'))->sendKeys($password);

$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('login_btn'))->click();


$driver->wait(10)->until(
    \Facebook\WebDriver\WebDriverExpectedCondition::presenceOfElementLocated(\Facebook\WebDriver\WebDriverBy::id('menu_tools'))
);


$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('menu_tools'))->click();


$driver->wait(10)->until(
    \Facebook\WebDriver\WebDriverExpectedCondition::presenceOfElementLocated(\Facebook\WebDriver\WebDriverBy::id('sms_message_new'))
);

$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('sms_message_new'))->click();

$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('sms_send_user_input'))->sendKeys($number);

$driver->findElement(\Facebook\WebDriver\WebDriverBy::id('sms_current_content'))->sendKeys($text);

$driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('div[class="sms_send_normal"][onclick*="EMUI.smsSendAndSaveController.sendMessage()"]'))->click();

$driver->wait(30)->until(
    \Facebook\WebDriver\WebDriverExpectedCondition::invisibilityOfElementLocated(\Facebook\WebDriver\WebDriverBy::id('submit_light'))
);

$driver->quit();
