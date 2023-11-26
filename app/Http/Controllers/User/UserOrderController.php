<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderDetailRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class UserOrderController extends Controller
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
     * Display the specified resource.
     */
    public function show(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderDetailRequest $request, OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, OrderDetail $orderDetail)
    {
        //
    }
}
