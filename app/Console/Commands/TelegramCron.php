<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
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
        \Log::info("Cron funcionando!");

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

        $texto = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE"."\n";

        foreach ($dados as $dado) {

            $texto = $texto ."\n \xF0\x9F\x8F\x86 : " . $dado['liga'] . "\n"
                . " \xE2\x9A\xBD : ". $dado['time1'] . " x ". $dado['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $dado['hora']."\n"
                . " \xF0\x9F\x93\xBA : " . $dado['canal']. "\n"
                ."-------------------------------------------------------";
        }

        $this->sendMessage($texto);
        $this->info('Executado!');
    }

    public function sendMessage ($text)
    {
        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID', '375323134'),
            'parse_mode' => 'HTML',
            'text' => $text
        ]);
    }
}