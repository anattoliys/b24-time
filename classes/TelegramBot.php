<?php

class TelegramBot
{
    private $rate = 400;
    private $apiKey;

    public function __construct()
    {
        $apiKey = ApiKey::getKey('b24TimeBot');
        $this->apiKey = $apiKey;
    }

    public function greetings($chatId, $name)
    {
        $message = "<b>Привет, {$name}!</b>\n\n";
        $message .= 'Я буду присылать тебе статистику по времени за день'. "\n\n";
        $message .= 'Для этого, введи свой id в битрикс24:'. "\n\n";

        $this->sendMessage($chatId, $message, 'html');
    }

    /**
     * Sends a message to the telegram
     *
     * @param integer $dayTime
     * @param integer $monthTime
     * @return void
     */
    public function sendMessage($chatId, $message, $parseMode = '')
    {
        // $updates = $this->getUpdates();
        // $userId = $updates['message']['chat']['id'];
        // $userName = $updates['message']['chat']['first_name'];
        // $monthTimeHours = ConvertMinutes::exec($monthTime);
        // $money = number_format($monthTime * $this->rate / 60, 0, '.', ' ');

        // $text = "<b>Привет, {$userName}!</b>\n\n";
        // $text .= "Вот статистика по времени за сегодня (" . date('j.m.Y') . "):\n\n";
        // $text .= $this->unichr('U+231A') . ' За день - ' . $dayTime . "\n";
        // $text .= $this->unichr('U+1F555') . ' За месяц - ' . $monthTimeHours . "\n";
        // $text .= $this->unichr('U+1F4B5') . ' Сколько денег - ' . $money;

        $queryUrl = "https://api.telegram.org/bot{$this->apiKey}/sendMessage";
        $queryData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => $parseMode,
        ];

        $curlExec = CurlQuery::exec($queryUrl, $queryData);
    }

    /**
     * Converts unicode characters to utf-8
     *
     * @param string $i
     * @return string
     */
    protected function unichr($i)
    {
        return html_entity_decode(preg_replace("/U\+([0-9A-F]{4,5})/", "&#x\\1;", $i), ENT_NOQUOTES, 'UTF-8');
    }

    public function getWebhookInfo()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        // Log::getMessage($data);

        return $data;
    }
}
