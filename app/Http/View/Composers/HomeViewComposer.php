<?php

namespace App\Http\View\Composers;
use App\Models\Subscriber;
use Illuminate\View\View;
use Carbon\Carbon;

class HomeViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $usersActive = Subscriber::all()->count();
        $usersAll = Subscriber::onlyTrashed()->count() + $usersActive;

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

        $view->with('usersActive');
        $view->with('usersAll');
        $view->with('labelMeses');
        $view->with('dadosMeses');
    }
}
