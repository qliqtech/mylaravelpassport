<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestRedisController extends Controller
{
    //

    public function index()
    {


        return response('hello');
    }


}
