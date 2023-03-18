<?php

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Illuminate\Contracts\Console\Kernel;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiHeaders
{

    protected $formatJsonApiDocument = true;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro(
            'assertJsonApiValidationErrors', 
            $this->assertJsonApiValidationErrors()
        );
    }

    public function withoutJsonApiDocument() {
        $this->formatJsonApiDocument = false;
    }

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers['accept'] = 'application/vnd.api+json';

        if ($this->formatJsonApiDocument) {

            $formattedData['data']['attributes'] = $data;
        
            $formattedData['data']['type'] = (string) Str::of($uri)->after('api/v1/');

        }

        return parent::json($method, $uri, $formattedData ?? $data, $headers);
    }

    public function postJson($uri, array $data = [], array $headers = [])
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers);
    }

    public function patchJson($uri, array $data = [], array $headers = [])
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers);
    }

    protected function assertJsonApiValidationErrors()
    {
        return function($attribute){
            /** @var TestResponse $this */

            $pointer = Str::of($attribute)->startsWith('data')
                ? "/".str_replace('.', '/', $attribute)
                : "/data/attributes/{$attribute}";

            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'".
                    PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail("Failed to find a valid JSON:API error response.".
                    PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }
}