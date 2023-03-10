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
            $upload_image_name = '';
            if($request->file('file')){
                $upload_image = $request->file('file');
                $upload_image_name = rand().'-employe.'.$upload_image->getClientOriginalExtension();
                $upload_image->move(public_path('images/employe/'), $upload_image_name);
                $insert['image'] = $upload_image_name;
                $request->request->add(['image'=>$upload_image_name]);
            }else{
                $request->request->add(['image'=>'']);
            }
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
    public function update(Request $request, $id)
    {
        try{   
            $data = masterEmploye::find($id);
            if($request->file('file')){
                unlink(public_path('images/employe/'.$data->image));
                $upload_image = $request->file('file');
                $upload_image_name = rand().'-employe.'.$upload_image->getClientOriginalExtension();
                $upload_image->move(public_path('images/banner/'), $upload_image_name);
                $insert['image'] = $upload_image_name;
                $request->request->add(['image'=>$upload_image_name]);
            }            
            $data->update($request->all());
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(masterEmploye $masterEmploye)
    {
        //
    }
}
