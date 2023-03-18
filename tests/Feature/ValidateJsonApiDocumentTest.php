<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateJsonApiDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
    	parent::setUp();

        $this->withoutJsonApiDocument();

    	// Route::any | get, post, patch y delete
        Route::any('test_header', function(){
        	return "OK";
        })->middleware(ValidateJsonApiDocument::class);
    }

    /** @test */
    public function data_is_required()
    {
        $this->postJson('test_header', [])
        ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_header', [])
        ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_must_be_array()
    {
        $this->postJson('test_header', [
            'data' => 'string'
        ])
        ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_header', [
            'data' => 'string'
        ])
        ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_type_is_required()
    {
        $this->postJson('test_header', [])
        ->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_header', [])
        ->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_type_must_be_string()
    {
        $this->postJson('test_header', [
            'data' => [
                'type' => 1
            ]
        ])
        ->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_header', [
            'data' => [
                'type' => 1
            ]
        ])
        ->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_attributes_is_required()
    {
        $this->postJson('test_header', [
            'data' => [
                'type' => 'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_header', [
            'data' => [
                'type' => 'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_attributes_must_be_array()
    {
        $this->postJson('test_header', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_header', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_id_is_required()
    {

        $this->patchJson('test_header', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])
        ->assertJsonApiValidationErrors('data.id');
    }

    /** @test */
    public function data_id_must_be_string()
    {

        $this->patchJson('test_header', [
            'data' => [
                'id' => 1,
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])
        ->assertJsonApiValidationErrors('data.id');
    }
}
