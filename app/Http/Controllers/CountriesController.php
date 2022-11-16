<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
  
    public function index()
    {
        $countries = Countries::all();

        if ($countries)
            return response()->json([
                'success' => true,
                'message' => $countries
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Failed to get list of countries'
            ], 500);
    }

    public function show($id, Request $request)
    {
        $country = Countries::where('id', $id);

        if ($country->get())
            return response()->json([
                'success' => true,
                'message' => $country->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'No country found with this id'
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
            'season' => 'required'
        ]);


        $country = new Countries();
        $country->name = $request->name;
        $country->season = $request->season;

        if ($country->save())
            return response()->json([
                'success' => true,
                'message' => $country->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cant save country'
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
            'season' => 'required'
        ]);


        $country = Countries::where('id', $id);

        if ($country->update($request->all()))
            return response()->json([
                'success' => true,
                'message' => 'Country updated'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'cant save country'
            ], 500);
    }

    
    public function destroy($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        
        try {
            $country = Countries::where('id', $id);

            $country->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Country deleted'
            ]);
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The country cannot be deleted because it is assigned to a hotel'
            ], 500);
        }
    }
}