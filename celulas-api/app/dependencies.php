<?php

$container['TestController'] = function ($c) {
    return new App\Http\Controllers\TestController($c);
};

