<?php

/**
 * GitHub: RoyalHaze
 * Date: 10/4/23
 * Time: 12:56 AM
 **/

require 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class RouterSmsAutomation
{
    private $driver;
    private $routerIp;
    private $password;
    private $text;
    private $number;

    public function __construct($routerIp, $password, $text, $number)
    {
        $this->routerIp = $routerIp;
        $this->password = $password;
        $this->text = $text;
        $this->number = $number;

        // Initialize WebDriver
        $this->driver = RemoteWebDriver::create('http://127.0.0.1:4444', [
            'platform' => 'ANY',
            'browserName' => 'chrome',
        ]);
    }

    public function sendSms()
    {
        try {
            // Open the router login page
            $this->driver->get("http://{$this->routerIp}");

            // Wait for the page to load
            $this->driver->wait(10)->until(
                WebDriverExpectedCondition::titleContains('4G')
            );

            // Login to the router
            $this->driver->findElement(WebDriverBy::id('login_password'))->sendKeys($this->password);
            $this->driver->findElement(WebDriverBy::id('login_btn'))->click();

            // Wait for the tools menu to load
            $this->driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('menu_tools'))
            );

            // Navigate to SMS section
            $this->driver->findElement(WebDriverBy::id('menu_tools'))->click();
            $this->driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('sms_message_new'))
            );

            // Send SMS
            $this->driver->findElement(WebDriverBy::id('sms_message_new'))->click();
            $this->driver->findElement(WebDriverBy::id('sms_send_user_input'))->sendKeys($this->number);
            $this->driver->findElement(WebDriverBy::id('sms_current_content'))->sendKeys($this->text);
            $this->driver->findElement(WebDriverBy::cssSelector('div[class="sms_send_normal"][onclick*="EMUI.smsSendAndSaveController.sendMessage()"]'))->click();

            // Wait for the SMS to be sent
            $this->driver->wait(30)->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('submit_light'))
            );

            echo "SMS sent successfully.\n";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage() . "\n";
        } finally {
            // Ensure the WebDriver quits
            $this->driver->quit();
        }
    }
}

// Usage
$routerIp = '192.168.1.1';
$password = 'password';
$text = 'TEXT';
$number = 'NUM';

$automation = new RouterSmsAutomation($routerIp, $password, $text, $number);
$automation->sendSms();
