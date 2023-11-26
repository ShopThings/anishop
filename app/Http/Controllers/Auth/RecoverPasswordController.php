<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\CheckUserRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;

class RecoverPasswordController extends Controller
{
    public function store(CheckUserRequest $request)
    {

    }

    public function verifyCode(VerifyCodeRequest $request)
    {

    }

    public function assignPassword(NewPasswordRequest $request)
    {

    }
}
