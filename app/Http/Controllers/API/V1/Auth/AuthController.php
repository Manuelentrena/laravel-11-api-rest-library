<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Contracts\API\Auth\AuthServiceInterface;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
        parent::__construct();
    }
}
