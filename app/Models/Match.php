<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $table ='matches';

    protected $fillable = [
        'team1', 'team2','horary','channels','today','league_id'
    ];

    public function league () {
        return $this->belongsTo(League::class);
    }
}
