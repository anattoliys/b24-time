<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Autoloader.php';

$telegramBot = new TelegramBot;
$webhookData = $telegramBot->getWebhookData();
$telegramBot->dispatcher($webhookData);
