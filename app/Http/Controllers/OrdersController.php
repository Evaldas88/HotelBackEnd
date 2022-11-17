<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Orders;

use App\Models\Countries;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
   
    public function index()
    {
        $orders = Orders::where('user_id', auth()->user()->id)->get();

        $generatedOrders = [];

        foreach ($orders as $order) {
            $hotel = Hotels::find($order->hotel_id);
            if($hotel->country_id) {
                $country = Countries::find($hotel->country_id);
                $order['country_name'] = $country->name;
            } else {
                $order['country_name'] = 'Not assigned';
            }
            $order['hotel_name'] = $hotel->name;
            $order['price'] = $hotel->price;
            $order['photo'] = $hotel->photo;
            $order['travel_duration'] = $hotel->travel_duration;
            $generatedOrders[] = $order;
        }

        if ($generatedOrders) {
            return response()->json([
                'success' => true,
                'message' => $generatedOrders
            ]);
        } else {
            return response()->json([
                'success' => false,
                'Failed to get order list'
            ], 500);
        }
    }

    public function all()
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $orders = Orders::all();

        $generatedOrders = [];

        foreach ($orders as $order) {
            $hotel = Hotels::find($order->hotel_id);
            if($hotel->country_id) {
                $country = Countries::find($hotel->country_id);
                $order['country_name'] = $country->name;
            } else {
                $order['country_name'] = 'Not assigned';
            }
            $order['hotel_name'] = $hotel->name;
            $order['price'] = $hotel->price;
            $order['photo'] = $hotel->photo;
            $order['travel_duration'] = $hotel->travel_duration;
            $generatedOrders[] = $order;
        }

        return response()->json([
            'success' => true,
            'message' => $generatedOrders
        ]);
    }


   
    public function store(Request $request)
    {
        $this->validate($request, [
            'hotel_id' => 'required'
        ]);


        $order = new Orders();
        $order->hotel_id = $request->hotel_id;
        $order->approved = 0;
        $order->user_id = auth()->user()->id;

        if ($order->save())
            return response()->json([
                'success' => true,
                'message' => $order->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order'
            ], 500);
    }

    public function status($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $order = Orders::find($id);
        if ($order->update(['approved' => $order->approved === 0 ? 1 : 0]))
            return response()->json([
                'success' => true,
                'message' => 'The order has been successfully confirmed'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm order'
            ], 500);
    }


    
    public function destroy($id, Orders $orders)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $order = Orders::where('id', $id);

        if ($order->delete())
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order'
            ], 500);
    }
}