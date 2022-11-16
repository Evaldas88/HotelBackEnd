<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelsController extends Controller
{
 
    public function index()
    {
        $hotels = Hotels::all();

        foreach ($hotels as $hotel) {
            if($hotel->country_id) {
                $country = Countries::find($hotel->country_id);
                $hotel->country = $country->name;
            } else {
                $hotel->country = 'Not selected';
            }
        }

        if ($hotels)
            return response()->json([
                'success' => true,
                'message' => $hotels
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to get hotel list'
            ], 500);
    }
    public function show($id, Request $request)
    {

        $hotel = Hotels::where('id', $id);

        if ($hotel->get())
            return response()->json([
                'success' => true,
                'message' => $hotel->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'The hotel could not be found'
            ], 500);
    }

    
    public function store(Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'travel_duration' => 'required'
        ]);
        $hotel = new Hotels();
        $hotel->name = $request->name;
        $hotel->price = number_format($request->price, 2, '.');
        $hotel->travel_duration = $request->travel_duration;
        $hotel->image = $request->image;
        if ($request->country_id) {
            $hotel->country_id = $request->country_id;
        } else {
            $hotel->country_id = null;
        }
        
        if ($hotel->save())
            return response()->json([
                'success' => true,
                'message' => $hotel->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to save hotel'
            ], 500);
            
    }

     
    public function update($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'travel_duration' => 'required'
        ]);

        $hotel = Hotels::find($id);
        
        if ($request->country_id) {
            $data['country_id'] = $request->country_id;
        } else {
            $data['country_id'] = null;
        }

       
         $hotel->update($request->all());
        return $hotel;
    }

    
    public function destroy($id)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $hotel = Hotels::where('id', $id);

        if ($hotel->delete())
            return response()->json([
                'success' => true,
                'message' => 'Hotel deleted successfully'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' =>'Failed to delete hotel'
            ], 500);
    }

    public function byCountry($id)
    {
        $hotels = Hotels::where('country_id', $id);

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to get hotel list'
            ], 500);
    }

    public function sortByPrice()
    {
        $hotels = Hotels::orderBy('price');

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' =>'Failed to get hotel list'
            ], 500);
    }
    public function search($keyword)
    {
        $hotels = Hotels::where('name', 'like', '%' . $keyword . '%');

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' =>'Failed to get hotel list'
            ], 500);
    }
}