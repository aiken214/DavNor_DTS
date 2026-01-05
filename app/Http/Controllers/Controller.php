<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controllers\Middleware; // for using the middleware
use Illuminate\Routing\Controllers\HasMiddleware;
//use Illuminate\Routing\Controller as BaseController;


abstract class Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth_gates',];
    }
    
}
