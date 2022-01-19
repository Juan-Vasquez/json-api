<?php

namespace Tests\Feature\Articles;

use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListArticleTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_a_single_articles()
    {
    	$this->withoutExceptionHandling();

    	$article = factory(Article::class)->create();

    	$response = $this->getJson(route('api.v1.show', $article));

    	$response->assertExactJson([
    		'data' => [
    			'type' => 'articles',
    			'id' => (string) $article->getRouteKey(),
    			'attributes' => [
    				'title' => $article->title,
    				'slug' => $article->slug,
    				'content' => $article->content
    			],
    			'links' => [
    				'self' => route('api.v1.show', $article)
    			]
    		]
    	]);
    }
}
