<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

class ArticleController extends Controller
{
	public function index()
	{
		return ArticleCollection::make(Article::all());
	}

   	public function show(Article $article)
   	{
   		return ArticleResource::make($article);
   	}
}
