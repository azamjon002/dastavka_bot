<?php
require_once  __DIR__ . '/vendor/autoload.php';
include __DIR__. '/language.php';

$botToken = "6158259501:AAFLeTgnV1KRqFs9sdyEemHTAxbrWFU8i8o";
// https://api.telegram.org/bot6158259501:AAFLeTgnV1KRqFs9sdyEemHTAxbrWFU8i8o/setWebhook?url=https://64de-188-113-230-13.in.ngrok.io/dastavka_bot/index.php

$bot = new \TelegramBot\Api\Client($botToken);
/**
 * @var $bot \TelegramBot\Api\Client | \TelegramBot\Api\BotApi
 */

$connection = mysqli_connect('localhost', 'newuser', 'password', 'dastavka');


$bot->command('start', static function (\TelegramBot\Api\Types\Message $message) use ($bot, $connection) {
    try {
        $chatId = $message->getChat()->getId();


        $sql_chat_id = $connection->query("select chat_id from users")->num_rows;

        if ($sql_chat_id == 0){
            $connection->query("insert into users (chat_id) values ('$chatId')");
            $btn  = new  \TelegramBot\Api\Types\ReplyKeyboardMarkup([[['text'=>'Uzbek 🇺🇿'],['text'=>'Русский 🇷🇺']]], true, true);
            $bot->sendMessage($chatId, "Tilni tanlang\nВыберите язык⬇️⬇️⬇️", 'HTML', null, false, $btn);
        }else{
            ////
        }
    } catch (Exception $exception) {
        //
    }
});


$bot->callbackQuery(static function (\TelegramBot\Api\Types\CallbackQuery $callbackquery) use ($bot) {
    try {

        $chatId = $callbackquery->getMessage()->getChat()->getId();
        $data = $callbackquery->getData();
        $firstname = $callbackquery->getMessage()->getChat()->getFirstName();
        $messageId = $callbackquery->getMessage()->getMessageId();

//        if ($data == 'orqa'){
//            $bot->deleteMessage($chatId, $messageId);
//        }

    } catch (Exception $exception) {
    }
});


$bot->on(static function () {
},
    static function (\TelegramBot\Api\Types\Update $update) use ($bot, $connection, $languages) {

        try {
            $chat_id = $update->getMessage()->getChat()->getId();
            $text = $update->getMessage()->getText();
            $messageId = $update->getMessage()->getMessageId();
            $status = $connection->query("select status from users where('chat_id', '=', $chat_id)")->fetch_assoc();
            var_dump($status);

            $remove_btn = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);

            if ($text == 'Uzbek 🇺🇿') {
                $connection->query("update users set til = 'uz', status = 'name'");
                $bot->sendMessage($chat_id, $languages['uz']['name'], 'HTML', null, false, $remove_btn);
            }
            if ($text == 'Русский 🇷🇺') {
                $connection->query("update users set til = 'ru', status = 'name'");
                $bot->sendMessage($chat_id, $languages['ru']['name'], 'HTML', null, false, $remove_btn);
            }

        } catch (Exception $exception) {
        }
    });


$bot->run();