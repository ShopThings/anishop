<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnOrderRequest;
use App\Http\Requests\UpdateReturnOrderRequest;
use App\Models\OrderDetail;
use App\Models\ReturnOrderRequest;
use Illuminate\Http\Request;

class UserReturnOrderRequestController extends Controller
{
    public function latest(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReturnOrderRequest $request, OrderDetail $order)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnOrderRequest $returnOrderRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReturnOrderRequest $request, ReturnOrderRequest $returnOrderRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ReturnOrderRequest $returnOrderRequest)
    {
        //
    }
}
