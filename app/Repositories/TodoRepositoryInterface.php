<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface  TodoRepositoryInterface
{
    public function store(Request $request);

}
