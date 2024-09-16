<?php

namespace App\Attributes;

use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

#[Attribute(Attribute::TARGET_METHOD)]
class UnauthorizedResponseAttribute extends Response
{
    public function __construct()
    {
        parent::__construct(
            response: \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
            content: new JsonContent(
                schema: 'json',
                example: [
                    'message' => 'Unauthenticated',
                ]
            )
        );
    }
}
