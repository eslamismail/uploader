<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(str_replace('/backend', '', url('')));
});
