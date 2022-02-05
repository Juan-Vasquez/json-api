<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
    	parent::setUp();

    	// Route::any | get, post, patch y delete
        Route::any('test_header', function(){
        	return "OK";
        })->middleware(ValidateJsonApiHeaders::class);
    }

    /** @test */
    public function accept_header_must_be_present_in_all_request()
    {

        $this->get('test_header')->assertStatus(406);

        $this->get('test_header', [
        	'accept' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_post_request()
    {

    	$this->post('test_header', [], [
    		'accept' => 'application/vnd.api+json'
    	])->assertStatus(415);

    	$this->post('test_header', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_patch_request()
    {

    	$this->patch('test_header', [], [
    		'accept' => 'application/vnd.api+json'
    	])->assertStatus(415);

    	$this->patch('test_header', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_all_responses()
    {

    	$this->get('test_header', [
    		'accept' => 'application/vnd.api+json'
    	])->assertHeader('content-type', 'application/vnd.api+json');

    	$this->post('test_header', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertHeader('content-type', 'application/vnd.api+json');

    	$this->patch('test_header', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertHeader('content-type', 'application/vnd.api+json');
    }

    /** @test */
    public function content_type_header_not_must_be_present_in_empty_response()
    {
    	Route::any('empty_response', function(){
    		return response()->noContent();
    	})->middleware(ValidateJsonApiHeaders::class);

    	$this->get('empty_response', [
    		'accept' => 'application/vnd.api+json'
    	])->assertHeaderMissing('content-type');

    	$this->post('empty_response', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertHeaderMissing('content-type');

    	$this->patch('empty_response', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertHeaderMissing('content-type');

    	$this->delete('empty_response', [], [
    		'accept' => 'application/vnd.api+json',
    		'content-type' => 'application/vnd.api+json'
    	])->assertHeaderMissing('content-type');
    }
}
