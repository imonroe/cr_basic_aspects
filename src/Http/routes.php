<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use imonroe\cr_basic_aspects\Http\Controllers\CrbasicaspectsController;

Route::namespace('imonroe\cr_basic_aspects\Http\Controllers')->group(
    function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/cr_basic_aspects/demo', 'CrbasicaspectsController@demo');
        });
    }
);
