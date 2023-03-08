<?php

namespace App\Http\Controllers;

use App\Models\masterEmploye;
use Illuminate\Http\Request;

class MasterEmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = masterEmploye::all();
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
            $data = masterEmploye::create($request->all());
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_employe)
    {
        try{
            $data = masterEmploye::find($id_employe);
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(masterEmploye $masterEmploye)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, masterEmploye $masterEmploye)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(masterEmploye $masterEmploye)
    {
        //
    }
}
