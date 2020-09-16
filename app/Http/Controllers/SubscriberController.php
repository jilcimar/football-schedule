<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::orderBY('updated_at', 'desc')->paginate(10);
        return view('pages.subscribers.index', compact('subscribers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWarning()
    {
        $subscribers = Subscriber::orderBY('updated_at', 'desc')->get();
        return view('pages.subscribers.warning', compact('subscribers'));
    }

    /**
     * Dispara envio de notificação para os usuários.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendWarning(Request $request)
    {
        $text = "\xF0\x9F\x9A\xA7	ATUALIZAÇÃO! \xF0\x9F\x9A\xA7 "."\n \n".$request->text;

        if ($request->type == Subscriber::TODOS_USUARIOS) {
            $subscribers = Subscriber::all();
            if(env('MODE_TEST')) {
                Telegram::sendMessage([
                    'chat_id' => env('CHAT_TEST','375323134'),
                    'parse_mode' => 'HTML',
                    'text' => $text
                ]);
            } else {
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

            return redirect()->back()->with('success', 'Aviso enviado!');
        }

        $chatId = env('MODE_TEST')?env('CHAT_TEST','375323134'):$request->type;
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        return redirect()->back()->with('success', 'Aviso enviado!');
    }
}
