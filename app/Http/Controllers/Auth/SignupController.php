<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;

class SignupController extends Controller
{
    public function store(SignupRequest $request)
    {

    }

    public function verifyCode(VerifyCodeRequest $request)
    {

    }

    public function assignPassword(NewPasswordRequest $request)
    {

    }
}
