<?php

namespace app;

use app\models\User;
use app\utils\CurlQuery;
use app\utils\Converter;
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
            case '/help':
                $this->getHelp($userData);
                break;
            case '/gettime':
                $this->sendStatistic($userData);
                break;
            case '/subscribe':
                $this->subscribe($userData);
                break;
            case '/getinfo':
                $this->getInfo($userData);
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
    private function conclusion($userData, $message)
    {
        $user = new User;
        $user->update($userData['id'], ['b24Id' => $message]);

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
        $text .= $this->unichr('U+1F4B5') . ' Сколько денег - ' . $data['money'] . "\n";
        $text .= "\n" . '/help - команды бота';

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
        $totalTime = 0;

        $text = "<b>Привет, {$recipient['name']}!</b>\n\n";
        $text .= "Вот статистика по времени за сегодня (" . date('j.m.Y') . "):\n\n";

        foreach ($data as $user) {
            $text .=  $this->unichr($this->emoji[array_rand($this->emoji)]) . ' ' . $user['name'] . ': за день - ' . $user['dayTime'] . ', за месяц - '  . $user['monthTime'] . "\n";

            if ($user['position'] != 'manager') {
                $totalTime += $user['monthTimeSeconds'];
            }
        }

        $text .= "\n" . 'Всего за месяц - ' . Converter::minutesByFormat($totalTime) . "\n";

        $text .= "\n" . '/help - команды бота';

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
        $subscribe = $data['active'] ? 'отписаться' : 'подписаться';

        $text = "Некоторые вещи, которые умеет бот:\n\n";
        $text .= "/gettime - получить время\n";
        $text .= "/getinfo - мои данные\n";
        $text .= '/subscribe - ' . $subscribe . "\n";

        $this->sendMessage($data['chatId'], $text, 'html');
    }

    /**
     * Subscribe or unsubscribe from the sending of time 
     *
     * @param array $data
     * @return void
     */
    private function subscribe($data)
    {
        $active = 1;
        $subscribe = 'подписались ';
        $smile = $this->unichr('U+1F44D');

        if ($data['active']) {
            $active = 0;
            $subscribe = 'отписались ';
            $smile = $this->unichr('U+1F641');
        }

        $user = new User;
        $user->update($data['id'], ['active' => $active]);

        $text = "Вы " . $subscribe . $smile;

        $this->sendMessage($data['chatId'], $text, 'html');
    }

    /**
     * Gets user info
     *
     * @param array $data
     * @return void
     */
    private function getInfo($data)
    {
        $text = "Мои данные:\n\n";
        $text .= 'id в б24 - ' . $data['b24Id'] . "\n";
        $text .= 'ставка - ' . $data['rate'] . "\n";
        $text .= 'должность - ' . $data['position'] . "\n";

        $this->sendMessage($data['chatId'], $text, 'html');
    }

    /**
     * Sends a message to the telegram
     *
     * @param integer $dayTime
     * @param integer $monthTime
     * @return void
     */
    private function sendMessage($chatId, $text, $parseMode = '')
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
    private function unichr($i)
    {
        return html_entity_decode(preg_replace("/U\+([0-9A-F]{4,5})/", "&#x\\1;", $i), ENT_NOQUOTES, 'UTF-8');
    }
}
