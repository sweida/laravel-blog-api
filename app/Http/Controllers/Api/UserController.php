<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(UserRequest $request){
        // $request_params=$request->all();
        return ['msg' => 'aa'];
    }
}
