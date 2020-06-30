<?php

Route::group(['namespace' => 'Gsferro\Select2Easy\Http\Controllers', 'middleware' => ['web']], function()
{
    Route::get('/select2easy', 'Select2EasyController')->name('select2easy');
});

Route::get('/select2easy-demo', function() {
    return view( 'select2easy::select2easy-demo' );
});