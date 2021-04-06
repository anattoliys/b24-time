<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/Autoloader.php';

$telegramBot = new TelegramBot;
$webhookData = $telegramBot->getWebhookData();
$telegramBot->dispatcher($webhookData);
