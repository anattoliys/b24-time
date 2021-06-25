<?php

namespace app;

use app\models\User;
use app\utils\CurlQuery;
use app\core\Log;

class TelegramBot
{
    private $emoji;

    public function __construct()
    {
        $emojiFolder = $_SERVER['DOCUMENT_ROOT'] . '/app/utils/emoji.php';
        $this->emoji = require $emojiFolder;
    }

    /**
     * Dispatcher
     *
     * @param array $data
     * @return void
     */
    public function dispatcher($data)
    {
        $chatId = isset($data['message']['chat']['id']) ? $data['message']['chat']['id'] : 0;
        $firstName = isset($data['message']['chat']['first_name']) ? $data['message']['chat']['first_name'] : '';
        $message = isset($data['message']['text']) ? $data['message']['text'] : '';
        $updateId = isset($data['update_id']) ? $data['update_id'] : 0;
        $dbUpdateId = User::getUpdateId($chatId);
        $user = new User;
        $userData = $user->getList(['chatId' => $chatId], true)[0];

        switch ($message):
            case '/start':
                $this->greetings($chatId, $firstName, $updateId);
                break;
            case '/gettime':
                $this->sendStatistic($userData);
                break;
            case '/help':
                $this->getHelp($userData);
                break;
        endswitch;

        if ($dbUpdateId + 1 == $updateId) {
            $this->conclusion($userData, $message);
        }
    }

    /**
     * Greeting the bot
     *
     * @param integer $chatId
     * @param string $name
     * @param integer $updateId
     * @return void
     */
    private function greetings($chatId, $name, $updateId)
    {
        $user = new User;
        $userId = User::getId($chatId);

        if ($userId) {
            $text = 'Вы уже указали id в битрикс 24';
        } else {
            $user->create($chatId, $name, $updateId);

            $text = "<b>Привет, {$name}!</b>\n\n";
            $text .= 'Я буду присылать тебе статистику по времени за день'. "\n\n";
            $text .= 'Для этого, введи свой id в битрикс 24:'. "\n\n";
        }

        $this->sendMessage($chatId, $text, 'html');
    }

    /**
     * Conclusion the bot
     *
     * @param array $userData
     * @param string $message
     * @return void
     */
    protected function conclusion($userData, $message)
    {
        $user = new User;
        $user->setB24Id($userData['chatId'], $message);

        $text = 'Отлично! ' . $this->unichr('U+1F642') . "\n\n";
        $text .= 'Сообщения будут приходить в 13:00 и 19:00';

        $this->sendMessage($userData['chatId'], $text, 'html');
    }

    /**
     * Sends statistics by user time
     *
     * @param array $data
     * @return void
     */
    public function sendStatistic($data)
    {
        $text = "<b>Привет, {$data['name']}!</b>\n\n";
        $text .= "Вот статистика по времени за сегодня (" . date('j.m.Y') . "):\n\n";
        $text .= $this->unichr('U+231A') . ' За день - ' . $data['dayTime'] . "\n";
        $text .= $this->unichr('U+1F555') . ' За месяц - ' . $data['monthTime'] . "\n";
        $text .= $this->unichr('U+1F4B5') . ' Сколько денег - ' . $data['money'] . "\n\n";
        $text .= '/help - посмотреть все команды';

        $this->sendMessage($data['chatId'], $text, 'html');
    }

    /**
     * Sends statistics by all users time
     *
     * @param array $data
     * @param array $chatId
     * @return void
     */
    public function sendStatisticsByAllUsers($data, $recipient)
    {
        $text = "<b>Привет, {$recipient['name']}!</b>\n\n";
        $text .= "Вот статистика по времени за сегодня (" . date('j.m.Y') . "):\n\n";

        foreach ($data as $user) {
            $text .=  $this->unichr($this->emoji[array_rand($this->emoji)]) . ' ' . $user['name'] . ': за день - ' . $user['dayTime'] . ', за месяц - '  . $user['monthTime'] . "\n";
        }

        $this->sendMessage($recipient['chatId'], $text, 'html');
    }

    /**
     * Get help
     *
     * @param array $data
     * @return void
     */
    private function getHelp($data)
    {
        $text = "Некоторые вещи, которые умеет бот:\n\n";
        $text .= "/gettime - получить время\n";

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
        $queryUrl = 'https://api.telegram.org/bot' . TELEGRAM_BOT_API_KEY . '/sendMessage';
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
