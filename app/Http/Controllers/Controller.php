<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Weidner\Goutte\GoutteFacade;

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
        dd( $dados);

    }
}
