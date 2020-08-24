<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Weidner\Goutte\GoutteFacade;
use Telegram\Bot\Laravel\Facades\Telegram;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDados() {
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
        return \GuzzleHttp\json_encode($dados);
    }

    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        dd($activity);
        foreach ($activity as $a) {
            if($a->message and $a->message->from->id and $a->message->from->first_name) {
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $a->message->from->id ,
                    ],
                    [
                        'chat_id' => $a->message->from->id,
                        'username' =>$a->message->from->username,
                        'first_name' =>$a->message->from->first_name,
                        'language_code' => $a->message->from->language_code,
                    ]
                );
            }
        }

        return \GuzzleHttp\json_encode($activity);
    }
}
