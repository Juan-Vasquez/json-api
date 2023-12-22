<?php

namespace Tests\Feature\Articles;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_articles()
    {
    	$this->withoutExceptionHandling();

        $article = factory(Article::class)->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update articulo',
            'slug' => 'update-articulo',
            'content' => 'Actualizando articulo'
        ])->assertOk();

        $response->assertHeader(
        	'Location',
        	route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
        	'data' => [
        		'type' => 'articles',
        		'id' => (string) $article->getRouteKey(),
        		'attributes' => [
        			'title' => 'Update articulo',
                    'slug' => 'update-articulo',
                    'content' => 'Actualizando articulo'
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

        $article = factory(Article::class)->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            // 'title' => 'Nuevo articulo',
            'slug' => 'nuevo-articulo',
            'content' => 'Este es un nuevo articulo',
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_min_4_characteres()
    {

        $article = factory(Article::class)->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nue',
            'slug' => 'nuevo-articulo',
            'content' => 'Este es un nuevo articulo',
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required()
    {

        $article = factory(Article::class)->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            // 'slug' => 'nuevo-articulo',
            'content' => 'Este es un nuevo articulo',
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {

        $article = factory(Article::class)->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => 'nuevo-articulo',
            // 'content' => 'Este es un nuevo articulo',
        ])->assertJsonApiValidationErrors('content');
    }

}
