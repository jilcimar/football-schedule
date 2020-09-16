<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usersActive = Subscriber::all()->count();

        $labelMeses = [];
        $dadosMeses = [];

        $groupByMonth = Subscriber::select('id', 'created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        foreach ($groupByMonth as $key=> $data) {
            $labelMeses[] = date("F", mktime(null, null, null, $key, 1));
            $dadosMeses[] = count($data);
        }
        return view('home', compact('usersActive', 'labelMeses','dadosMeses'));
    }
}
