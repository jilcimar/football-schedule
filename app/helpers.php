<?php

use App\Models\Subscriber;
use Telegram\Bot\Laravel\Facades\Telegram;

if (!function_exists('cleaningGames')) {
    /**
     *
     *
     * @return boolean
     */
    function cleaningGames($array) {
       return  (strpos($array, 'Russo') == false
        and strpos($array, 'Bielorrusso') == false
        and strpos($array, 'Série B') == false
        and strpos($array, 'Série C') == false
        and strpos($array, 'Série D') == false
        and strpos($array, 'Sub-20') == false
        and strpos($array, 'A3') == false
        and strpos($array, '2ª') == false
        and strpos($array, 'MX') == false
        and strpos($array, 'Chinesa') == false
        and strpos($array, 'Aspirantes') == false
        and strpos($array, 'Escocês') == false
        and strpos($array, 'Turco') == false
        and strpos($array, 'MLS') == false
        and strpos($array, 'Feminino') == false
        and strpos($array, '2') == false);
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage ($text) {
        if(env('MODE_TEST')) {
            Telegram::sendMessage([
                'chat_id' => env('CHAT_TEST'),
                'parse_mode' => 'HTML',
                'text' => $text
            ]);
        } else {
            $subscribers = Subscriber::where('daily_notification', true)->get();

            foreach ($subscribers as $subscriber) {
                try {
                    Telegram::sendMessage([
                        'chat_id' => $subscriber->chat_id,
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]);
                } catch (\Exception $exception) {
                    $subscriberBlock = Subscriber::where('chat_id',$subscriber->chat_id)->first();
                    $subscriberBlock->delete();
                    \Log::info("Erro CHAT: ". $subscriber->chat_id);
                }
            }
        }
    }
}
