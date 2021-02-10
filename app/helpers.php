<?php

use Illuminate\Support\Str;

if (!function_exists('cleaningGames')) {
    /**
     *
     *
     * @return boolean
     */
    function cleaningGames($array)
    {
       return  (strpos($array, 'Russo') == false
        and strpos($array, 'Bielorrusso') == false
        and strpos($array, 'Série B') == false
        and strpos($array, 'Série C') == false
        and strpos($array, 'Série D') == false
        and strpos($array, 'Sub-20') == false
        and strpos($array, 'A3') == false
        and strpos($array, '2ª') == false
        and strpos($array, 'MX') == false
        and strpos($array, 'Chinesa') == false
        and strpos($array, 'Aspirantes') == false
        and strpos($array, 'Escocês') == false
        and strpos($array, 'Turco') == false
        and strpos($array, 'MLS') == false
        and strpos($array, 'Feminino') == false
        and strpos($array, '2') == false);
    }
}
