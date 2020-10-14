<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use SoftDeletes;

    protected $table ='leagues';

    protected $fillable = [
        'name', 'country'
    ];

    public function matches () {
        return $this->hasMany(Match::class);
    }

}
