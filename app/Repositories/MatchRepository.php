<?php

namespace App\Repositories;

use App\Models\League;
use App\Models\Match;
use App\Models\User;

class  MatchRepository extends AbstractRepository
{
    protected $model = Match::class;

    public function store ($match , $hoje = true) {
        $league = League::updateOrCreate(
            [
                'name' => $match['league'],
            ],
            [
                'name' => $match['league'],
            ]
        );

        $this->model->create([
            'team1' => $match['team1'],
            'team2' => $match['team2'],
            'horary' => $match['time'],
            'channels' => $match['channel'],
            'today' => $hoje,
            'league_id' => $league->id
        ]);
    }
}
