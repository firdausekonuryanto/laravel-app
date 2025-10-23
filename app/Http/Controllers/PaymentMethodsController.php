<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethods;
use App\Http\Requests\StorePaymentMethodsRequest;
use App\Http\Requests\UpdatePaymentMethodsRequest;

class PaymentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethods $paymentMethods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethods $paymentMethods)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodsRequest $request, PaymentMethods $paymentMethods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethods $paymentMethods)
    {
        //
    }
}
