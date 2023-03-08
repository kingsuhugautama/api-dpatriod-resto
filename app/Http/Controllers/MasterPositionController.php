<?php

namespace App\Http\Controllers;

use App\Models\masterPosition;
use Illuminate\Http\Request;

class MasterPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = masterPosition::all();
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
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
        try{
            $data = masterPosition::create($request->all());
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_position)
    {
        try{
            $data = masterPosition::find($id_position);
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(masterPosition $masterPosition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, masterPosition $masterPosition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(masterPosition $masterPosition)
    {
        //
    }
}
