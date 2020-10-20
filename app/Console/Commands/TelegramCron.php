<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Match;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
                    return trim($td->text());
                });

                //Eliminando os Campeonatos
                if( strpos($liga[$i][0], 'Russo') == false
                    and strpos($liga[$i][0], 'Bielorrusso') == false
                    and strpos($liga[$i][0], 'Série B') == false
                    and strpos($liga[$i][0], 'Série C') == false
                    and strpos($liga[$i][0], 'Série D') == false
                    and strpos($liga[$i][0], 'Sub-20') == false
                    and strpos($liga[$i][0], 'A3') == false
                    and strpos($liga[$i][0], '2ª') == false
                    and strpos($liga[$i][0], 'MX') == false
                    and strpos($liga[$i][0], 'Feminino') == false ) {

                    $time1 = isset($time[$i][0]) ? $time[$i][0] :'';
                    $time2 = isset($time[$i][1]) ? $time[$i][1] :'';
                    $canal = isset($time[$i][2]) ? $time[$i][2] :'';

                    $dados['liga'] = $liga[$i][0];
                    $dados['time1'] = preg_replace('/[0-9]+/', '', $time1);
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $time2);
                    $dados['hora'] = $horario[$i][0];
                    $dados['canal'] = $canal;
                    return $dados;
                }
            });

        $dados =  array_filter($dados);

        DB::table('matches')->delete();

        foreach ($dados as $jogo) {
            $this->saveMatches($jogo);
        }

        //SALVANDO OS JOGOS DE AMANHÃ
        $dados = $crawler->filter('.table-bordered')
            ->eq(1)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){
                //Pegando os campos específicos
                $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });

                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
                    return trim($td->text());
                });

                //Eliminando os Campeonatos
                if( strpos($liga[$i][0], 'Russo') == false
                    and strpos($liga[$i][0], 'Bielorrusso') == false
                    and strpos($liga[$i][0], 'Série B') == false
                    and strpos($liga[$i][0], 'Série C') == false
                    and strpos($liga[$i][0], 'Série D') == false
                    and strpos($liga[$i][0], 'Sub-20') == false
                    and strpos($liga[$i][0], 'A3') == false
                    and strpos($liga[$i][0], '2ª') == false
                    and strpos($liga[$i][0], 'MX') == false
                    and strpos($liga[$i][0], 'Chinesa') == false
                    and strpos($liga[$i][0], 'Aspirantes') == false
                    and strpos($liga[$i][0], 'Escocês') == false
                    and strpos($liga[$i][0], 'Turco') == false
                    and strpos($liga[$i][0], 'Feminino') == false ) {

                    $time1 = isset($time[$i][0]) ? $time[$i][0] :'';
                    $time2 = isset($time[$i][1]) ? $time[$i][1] :'';
                    $canal = isset($time[$i][2]) ? $time[$i][2] :'';

                    $dados['liga'] = $liga[$i][0];
                    $dados['time1'] = preg_replace('/[0-9]+/', '', $time1);
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $time2);
                    $dados['hora'] = $horario[$i][0];
                    $dados['canal'] = $canal;
                    return $dados;
                }
            });

        $dados =  array_filter($dados);
        foreach ($dados as $jogo) {
            $this->saveMatches($jogo, false);
        }

        //ENVIANDO A MENSAGEM A PARTIR DO BANCO

        $jogos = Match::where('today', true)->get()->chunk(15);

        if(count($jogos)==0) {
            $this->sendMessage("Sem jogos hoje! \n \xF0\x9F\x91\x89 /jogosamanha - Lista de jogos de amanhã");
        };

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE ".$date."\n";

        foreach ($jogos[0] as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                ."-------------------------------------------------------";
        }

        $this->sendMessage($firstPart);

        //2 Mensagem
        if(isset($jogos[1])) {
            $secondPart ='';
            foreach ($jogos[1] as $jogo) {
                $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                    ."-------------------------------------------------------";
            }
            $this->sendMessage($secondPart);
        }

        //3 Mensagem
        if(isset($jogos[2])) {
            $thirdPart ='';
            foreach ($jogos[2] as $jogo) {
                $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                    ."-------------------------------------------------------";
            }
            $this->sendMessage($thirdPart);
        }

//        $this->sendMessage("\xF0\x9F\x91\x89  /jogosamanha - Lista de jogos de amanhã");

    }

    public function sendMessage ($text)
    {
        $subscribers = Subscriber::all();

        if(env('MODE_TEST'))
        {
            Telegram::sendMessage([
                'chat_id' => env('CHAT_TEST','375323134'),
                'parse_mode' => 'HTML',
                'text' => $text
            ]);
        }
        else
        {
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


    /**
     * Método para salvar os jogos no banco
     *
     *
     */

    public function saveMatches ($jogo , $hoje = true) {
        $league = League::updateOrCreate(
            [
                'name' => $jogo['liga'],
            ],
            [
                'name' => $jogo['liga'],
            ]
        );

        Match::create([
            'team1' => $jogo['time1'],
            'team2'=>$jogo['time2'],
            'horary'=>$jogo['hora'],
            'channels'=>$jogo['canal'],
            'today'=>$hoje,
            'league_id'=> $league->id
        ]);
    }
}
