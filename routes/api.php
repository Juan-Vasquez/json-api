<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('articles/{article}', 'Api\ArticleController@show')->name('api.v1.show');