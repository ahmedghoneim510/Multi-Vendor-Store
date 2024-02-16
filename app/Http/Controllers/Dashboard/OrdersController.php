<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request=request();
        $orders=Order::filter($request->query())->paginate(10);
        return view('dashboard.orders.index',compact('orders'));

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $order=Order::with('orderItems')->find($id);
        return view('dashboard.orders.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('dashboard.orders.edit',compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status'=>'required|in:pending,processing,completed,cancelled,refunded,delivered',
        ]);
        $order=Order::find($id);
        $order->update($request->all());
        return to_route('dashboard.orders-admins.index')->with('success','Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order=Order::find($id);
        $order->delete();
        return to_route('dashboard.orders-admins.index')->with('success','Order deleted successfully');
    }
}
