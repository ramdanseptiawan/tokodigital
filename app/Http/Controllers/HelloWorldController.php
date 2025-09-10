<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HelloWorldController extends Controller
{
    /**
     * Return a "Hello, World!" JSON response.
     */
    public function index()
    {
        return Response::json(['message' => 'Hello, World!']);
    }
}