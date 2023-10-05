<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use Illuminate\Http\Request;

class FestivalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Festival $festival)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Festival $festival)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Festival $festival)
    {
        //
    }

    public function batchDestroy(Request $request)
    {

    }

    public function products()
    {

    }

    public function storeProduct(Request $request)
    {

    }

    public function storeCategoryProducts(Request $request)
    {

    }

    public function destroyProduct(Request $request)
    {

    }

    public function batchDestroyProduct(Request $request)
    {

    }
}
