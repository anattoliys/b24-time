<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Autoloader.php';

$telegramBot = new TelegramBot;
$webhookInfo = $telegramBot->getWebhookInfo();

$chatId = $webhookInfo['message']['chat']['id'];
$firstName = $webhookInfo['message']['chat']['first_name'];
$message = $webhookInfo['message']['text'];

if ($message == '/start') {
    $telegramBot->greetings($chatId, $firstName);
}
