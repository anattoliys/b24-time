<?php

use app\TelegramBot;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$telegramBot = new TelegramBot;
$webhookData = $telegramBot->getWebhookData();
$telegramBot->dispatcher($webhookData);
