<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Contracts\API\Auth\AuthServiceInterface;
use App\Http\Controllers\API\V1\Controller;

class AuthController extends Controller
{
    public function __construct(protected readonly AuthServiceInterface $authService)
    {
        parent::__construct();
    }
}
