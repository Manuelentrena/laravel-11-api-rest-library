<?php

namespace App\Http\Controllers\API\V1;

use OpenApi\Attributes\Info;

#[Info(version: '1.0.0', title: 'Library API')]
abstract class Controller
{
    public function __construct()
    {
        ray()->showQueries();
    }
}
