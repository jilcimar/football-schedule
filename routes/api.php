<?php
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::post('bot/sendmessage', function() {
    Telegram::sendMessage([
        'chat_id' => 'RECIPIENT_CHAT_ID',
        'text' => 'Hello world!'
    ]);
    return;
});
