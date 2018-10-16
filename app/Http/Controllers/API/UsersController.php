<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UsersController extends Controller
{
    public function getUser()
    {
        return Auth::guard('api')->user();
    }
}
