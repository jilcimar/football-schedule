<?php

namespace App\Console\Commands;

use App\Models\Match;
use App\Repositories\MatchRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Weidner\Goutte\GoutteFacade;

class TelegramCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'collecting information from today matches';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        DB::table('matches')->delete();

        $model = new MatchRepository();

        $data = $this->scraping(0);
        $data =  array_filter($data);

        foreach ($data as $match) {
            $model->store($match);
        }

        $data = $this->scraping(1);
        $data =  array_filter($data);

        foreach ($data as $match) {
            $model->store($match, false);
        }

        $this->todayGames();
    }

    public function todayGames() {

        $matchs = Match::where('today', true)->get()->chunk(15);

        if(count($matchs)==0) {
            sendMessage("Sem jogos hoje! \n \xF0\x9F\x91\x89 /jogosamanha - Lista de jogos de amanhã");
            return;
        }

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE ".$date."\n";

        foreach ($matchs[0] as $match) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $match->league->name . "\n"
                . " \xE2\x9A\xBD : ". $match->team1 . " x ". $match->team2 ."\n"
                . " \xF0\x9F\x95\xA7 : ".$match->horary."\n"
                . " \xF0\x9F\x93\xBA : " . $match->channels. "\n"
                ."-------------------------------------------------------";
        }

        sendMessage($firstPart);

        //2 Part
        if(isset($matchs[1])) {
            $secondPart ='';
            foreach ($matchs[1] as $match) {
                $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " . $match->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $match->team1 . " x ". $match->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$match->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $match->channels. "\n"
                    ."-------------------------------------------------------";
            }
            sendMessage($secondPart);
        }

        //3 Part
        if(isset($matchs[2])) {
            $thirdPart ='';
            foreach ($matchs[2] as $match) {
                $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " . $match->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $match->team1 . " x ". $match->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$match->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $match->channels. "\n"
                    ."-------------------------------------------------------";
            }
            sendMessage($thirdPart);
        }

    }

    public function scraping($day) {
        $crawler = GoutteFacade::request('GET',
            'https://www.futebolnatv.com.br/');

        return $crawler->filter('.table-bordered')
            ->eq($day)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i) {
                $time[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });

                $league [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                if(cleaningGames($league[$i][0])) {
                    $data['league'] = $league[$i][0];
                    $data['team1'] = preg_replace('/[0-9]+/', '', $league[$i][1]);
                    $data['team2'] = preg_replace('/[0-9]+/', '', $league[$i][2]);
                    $data['time'] = $time[$i][0];
                    $data['channel'] = $league[$i][3];
                    return $data;
                }
            });
    }
}
