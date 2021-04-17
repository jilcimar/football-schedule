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
        $countUsers = Subscriber::onlyTrashed()->count() + Subscriber::all()->count();
        $usersNotificationActive = Subscriber::where('daily_notification', true)->count();
        $usersNotificationOff = Subscriber::where('daily_notification', false)->count();

        $labelMonths = [];
        $dataMonths = [];

        $groupByMonth = Subscriber::select('id', 'created_at')
            ->withTrashed()
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m/Y');
            });

        foreach ($groupByMonth as $key=> $data) {
            $labelMonths[] = $key;
            $dataMonths[] = count($data);
        }

        $view->with('usersNotificationActive', $usersNotificationActive);
        $view->with('usersNotificationOff', $usersNotificationOff);
        $view->with('countUsers', $countUsers);
        $view->with('labelMonths', $labelMonths);
        $view->with('dataMonths', $dataMonths);
    }
}
