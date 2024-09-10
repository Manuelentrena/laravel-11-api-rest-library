<?php

namespace App\Attributes;

use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

#[Attribute(Attribute::TARGET_METHOD)]
class ValidationErrorResponseAttribute extends Response
{
    public function __construct(array $errors)
    {
        parent::__construct(
            response: \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Validation error',
            content: new JsonContent(
                schema: 'json',
                example: [
                    'status' => 'error',
                    'message' => 'The given data was invalid',
                    'errors' => $errors,
                ]
            )
        );
    }
}
