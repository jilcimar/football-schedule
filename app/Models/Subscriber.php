<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    const TODOS_USUARIOS = 0;
    protected $table ='subscribers';

    protected $fillable = [
        'chat_id', 'username', 'language_code','first_name','group'
    ];
}
