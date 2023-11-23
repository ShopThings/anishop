<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

    }
}
