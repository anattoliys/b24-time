<?php

class TelegramBot
{
    private $apiKey;

    public function __construct()
    {
        $apiKey = ApiKey::getKey('b24TimeBot');
        $this->apiKey = $apiKey;
    }

    public function dispatcher($data, $sendStatistic = false)
    {
        $chatId = $data['message']['chat']['id'];
        $firstName = $data['message']['chat']['first_name'];
        $message = $data['message']['text'];
        $messagedId = $data['message']['message_id'];
        $startMessageId = User::getStartMessageId($chatId);

        if ($message == '/start') {
            $this->greetings($chatId, $firstName, $messagedId);
        } else if ($startMessageId + 2 == $messagedId) {
            $this->conclusion($chatId, $message);
        }

        if ($sendStatistic) {
            $this->sendStatistic($data);
        }
    }

    /**
     * Greeting the bot
     *
     * @param integer $chatId
     * @param string $name
     * @param integer $messagedId
     * @return void
     */
    protected function greetings($chatId, $name, $messagedId)
    {
        $user = new User;
        $userId = User::getByChatId($chatId);

        if ($userId) {
            $text = 'Вы уже указали id в битрикс 24';
        } else {
            $user->create($chatId, $name, $messagedId);

            $text = "<b>Привет, {$name}!</b>\n\n";
            $text .= 'Я буду присылать тебе статистику по времени за день'. "\n\n";
            $text .= 'Для этого, введи свой id в битрикс 24:'. "\n\n";
        }

        $this->sendMessage($chatId, $text, 'html');
    }

    /**
     * Conclusion the bot
     *
     * @param integer $chatId
     * @param string $message
     * @return void
     */
    protected function conclusion($chatId, $message)
    {
        $user = new User;
        $user->setB24Id($chatId, $message);

        $text = $this->unichr('U+1F642') . ' Отлично!' . "\n\n";
        $text .= 'Сообщения будут приходить в 13:00 и 19:00';

        $this->sendMessage($chatId, $text, 'html');
    }

    /**
     * Sends statistics by time
     *
     * @param array $data
     * @return void
     */
    protected function sendStatistic($data)
    {
        $monthTimeHours = Converter::convertMinutesByFormat($data['monthTime']);
        $money = number_format($data['monthTime'] * $data['rate'] / 60, 0, '.', ' ');

        $text = "<b>Привет, {$data['name']}!</b>\n\n";
        $text .= "Вот статистика по времени за сегодня (" . date('j.m.Y') . "):\n\n";
        $text .= $this->unichr('U+231A') . ' За день - ' . $data['dayTime'] . "\n";
        $text .= $this->unichr('U+1F555') . ' За месяц - ' . $monthTimeHours . "\n";
        $text .= $this->unichr('U+1F4B5') . ' Сколько денег - ' . $money;

        $this->sendMessage($data['chatId'], $text, 'html');
    }

    /**
     * Sends a message to the telegram
     *
     * @param integer $dayTime
     * @param integer $monthTime
     * @return void
     */
    protected function sendMessage($chatId, $text, $parseMode = '')
    {
        $queryUrl = "https://api.telegram.org/bot{$this->apiKey}/sendMessage";
        $queryData = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ];

        $curlExec = CurlQuery::exec($queryUrl, $queryData);
    }

    /**
     * Retrieves webhook data
     *
     * @return array
     */
    public function getWebhookData()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        return $data;
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
}
