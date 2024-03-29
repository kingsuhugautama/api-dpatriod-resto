<?php

namespace App\Http\Controllers;

use App\Models\masterBanner;
use Illuminate\Http\Request;
use File;
class MasterBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $data = masterBanner::all();
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
        //
        try{
            $upload_image_name = '';
            if($request->file('file')){
                $upload_image = $request->file('file');
                $upload_image_name = rand().'-banner.'.$upload_image->getClientOriginalExtension();
                $upload_image->move(public_path('images/banner/'), $upload_image_name);
                $insert['image'] = $upload_image_name;
                $request->request->add(['image'=>$upload_image_name]);
            }else{
                $request->request->add(['image'=>'']);
            }
            $data = masterBanner::create($request->all());
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $data = masterBanner::find($id);
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(masterBanner $masterBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        //
        try{   
            $data = masterBanner::find($id);
            if($request->file('file')){
                unlink(public_path('images/banner/'.$data->image));
                $upload_image = $request->file('file');
                $upload_image_name = rand().'-banner.'.$upload_image->getClientOriginalExtension();
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
     * Removesadasdad the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try{
            $data = masterBanner::find($id)->delete();
            return response()->json(['status'=>true,'data'=>$data]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false,'data'=>[],'message'=>$ex->getMessage()]);
        }
    }
}
