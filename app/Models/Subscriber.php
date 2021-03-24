<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    const TODOS_USUARIOS = 0;

    use SoftDeletes;

    protected $table ='subscribers';

    protected $fillable = [
        'chat_id', 'username', 'language_code','first_name','group', 'daily_notification'
    ];
}
