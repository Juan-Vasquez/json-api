<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('articles', 'Api\ArticleController@index')->name('api.v1.articles.index');
Route::get('articles/{article}', 'Api\ArticleController@show')->name('api.v1.articles.show');
Route::post('articles', 'Api\ArticleController@store')->name('api.v1.articles.store');
Route::patch('articles/{article}', 'Api\ArticleController@update')->name('api.v1.articles.update');