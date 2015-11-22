<?php


if (config('apimocker.should_mock')) {
    $endpoints = config('apimocker.endpoints');

    foreach ($endpoints as $path => $config) {
        Route::any($path, '\BaoPham\ApiMocker\ApiMockerController@mock');
    }
}
