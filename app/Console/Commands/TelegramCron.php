<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Weidner\Goutte\GoutteFacade;

class TelegramCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de mensagens para o bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron executando!");

        $crawler = GoutteFacade::request('GET',
            'https://www.futebolnatv.com.br/');

        $dados = $crawler->filter('.table-bordered')
            ->eq(0)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){
                //Pegando os campos específicos
                $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                $dados['liga']= $liga[$i][0];
                $dados['time1']= preg_replace('/[0-9]+/', '', $liga[$i][1]);
                $dados['time2']= preg_replace('/[0-9]+/', '', $liga[$i][2]);
                $dados['hora']= $horario[$i][0];
                $dados['canal']= $liga[$i][3];
                return $dados;
            });

        //Dividindo os dados para envio devido uma limitação no tamanho da mensagem
        $jogos = array_chunk($dados, 15);

        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE"."\n";

        foreach ($jogos[0] as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                ."-------------------------------------------------------";
        }

        $this->sendMessage($firstPart);

        //2 Mensagem
        if(isset($jogos[1])) {
            $secondPart ='';
            foreach ($jogos[1] as $jogo) {
                $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                    . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                    . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                    ."-------------------------------------------------------";
            }
            $this->sendMessage($secondPart);
        }

        //3 Mensagem
        if(isset($jogos[2])) {
            $thirdPart ='';
            foreach ($jogos[2] as $jogo) {
                $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                    . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                    . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                    ."-------------------------------------------------------";
            }
            $this->sendMessage($thirdPart);
        }

        $this->info('Executado!');
    }

    public function sendMessage ($text)
    {
//        $subscribers = Subscriber::all();
//
//        if(env('MODE_TEST'))
//        {
//            Telegram::sendMessage([
//                'chat_id' => env('CHAT_TEST','375323134'),
//                'parse_mode' => 'HTML',
//                'text' => $text
//            ]);
//        }
//        else
//        {
//            foreach ($subscribers as $subscriber) {
//                try {
//                    Telegram::sendMessage([
//                        'chat_id' => $subscriber->chat_id,
//                        'parse_mode' => 'HTML',
//                        'text' => $text
//                    ]);
//                } catch (\Exception $exception) {
//                    $subscriberBlock = Subscriber::where('chat_id',$subscriber->chat_id)->first();
//                    $subscriberBlock->delete();
//                    \Log::info("Erro CHAT: ". $subscriber->chat_id);
//                }
//            }
//        }
    }
}
