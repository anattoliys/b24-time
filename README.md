# b24-time bot

## Комманды
/start - подписаться на рассылку времени

## Файл /app/config/config.php
DB_TYPE - тип базы данных

DB_HOST - хост базы данных

DB_NAME - имя базы данных

DB_USER - Имя пользователя базы данных

DB_PASS - Пароль базы данных

B24_WEBHOOK - Входящий вебхук битрикс 24 с правами на Задачи (task)

TELEGRAM_BOT_API_KEY - api ключ бота

## Структура базы данных
### Таблица time
id - id элемента, Type = int(11), AUTO_INCREMENT

b24Id - id пользователя в битрикс 24, Type = int(11)

dayTime - Время за день в секундах, Type = int(11)

monthTime - Время за месяц в секундах, Type = int(11)

date - Дата создания элемента, Type = date

### Таблица users
id - id пользователя, Type = int(11), AUTO_INCREMENT

active - Активность, Type = bit(1), Default = 1

name - Имя, Type = varchar(255) utf8mb4_unicode_ci

chatId - id чата, int(11)

b24Id - id в битрикс 24, Type = int(11), Default = NULL

rate - Ставка, Type = int(11), Default = 400 (manager = 100)

position - Должность, Type = varchar(255) utf8mb4_unicode_ci, Default = programmer (manager, director)

startMessageId - id сообщения /start, int(11)

creationDate - дата создания, datetime
