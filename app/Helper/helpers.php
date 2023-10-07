<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('active_sidebar')){
    function active_sidebar(array $items){
        $route = Route::current()->uri;
        $data = [];

        foreach ($items as $value) {
            if ($value == 'panel')
            {
                $data[] = "panel";
            } else{
                $data[] = "panel/".$value;
            }
        }
        if (in_array($route, $data)) {
            return true;
        } else {
            return false;
        }
    }
}
