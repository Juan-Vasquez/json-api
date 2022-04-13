<?php

namespace Tests\Feature\Articles;

use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{    
    use RefreshDatabase;

    /** @test */
    public function can_store_articles()
    {
    	$this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.store'), [
        	'data' => [
        		'type' => 'articles',
        		'attributes' => [
        			'title' => 'Nuevo articulo',
        			'slug' => 'nuevo-articulo',
        			'content' => 'Este es un nuevo articulo',
        		]
        	]
        ]);

        $response->assertCreated();


        $article = Article::first();

        $response->assertHeader(
        	'Location',
        	route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
        	'data' => [
        		'type' => 'articles',
        		'id' => (string) $article->getRouteKey(),
        		'attributes' => [
        			'title' => 'Nuevo articulo',
        			'slug' => 'nuevo-articulo',
        			'content' => 'Este es un nuevo articulo',
        		],
        		'links' => [
        			'self' => route('api.v1.articles.show', $article)
        		]
        	]
        ]);
    }

    /** @test */
    public function title_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    // 'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Este es un nuevo articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_min_4_characteres()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nue',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Este es un nuevo articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    // 'slug' => 'nuevo-articulo',
                    'content' => 'Este es un nuevo articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    // 'content' => 'Este es un nuevo articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('content');
    }
}
