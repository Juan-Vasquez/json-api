<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidateJsonApiErrorResponses extends JsonResponse
{
    public function __construct(ValidationException $exception, $status = 422)
    {

        $data = $this->formatErros($exception);
            
        $headers = [
            'content-type' => 'application/vnd.api+json'
        ];

        parent::__construct($data, $status, $headers);
    }

    /**
     * @param ValidationException $exception
     * @return array
     */

    protected function formatErros($exception) : array
    {

        $title = $exception->getMessage();

        return [
            'errors' => collect($exception->errors())
                ->map(function($message, $field) use ($title) {
                    return [
                        'title' => $title,
                        'detail' => $message[0],
                        'source' => [
                            'pointer' => "/".str_replace('.','/',$field)
                        ]
                    ];
                })->values()
            ];
    }
}
