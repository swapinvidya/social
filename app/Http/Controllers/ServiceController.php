<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use File;

class ServiceController extends Controller
{
    public function index(){
        $service = service::all();
        return view ('admin.service',compact('service'));
    }

    public function add(Request $request){
        
        $done = Service::create($request->all());

        if ($done){

            $logo = time().'.'.$request->logo->getClientOriginalExtension();
            $path = './img/sociallogos/'.$logo;
            $request->logo->move('./img/sociallogos', $logo);
            $id = $done->id;
            Service::findOrFail($id)->update(['logo' => $path , 'status' => false]);
            return redirect()->back();
        }
        else{
            dd("Fatal error");
        }
    }

    public function mod( $id , Request $request){
        if ($request->input('pr') == 'en'){
            $done = service::findOrFail($id)->update(['status' => true]);
            if ($done){
                return response()->json(['msg'=>"Service enabled",'done' => true ],200);
            }
            else{
                return response()->json(['msg'=>"Error !",'Not done' => false ],401);
            }
        }
        else{
            $done = service::findOrFail($id)->update(['status' => false]);
            if ($done){
                return response()->json(['msg'=>"Service disabled",'done' => true ],200);
            }
            else{
                return response()->json(['msg'=>"Error !",'Not done' => false ],401);
            }
        }
        
    }

    public function edit_service(Request $request){
       if ($request->hasFile('logo')){
            File::delete(Service::find($request->input('id'))->logo);
            Service::findOrFail($request->input('id'))->update($request->all());
            $logo = time().'.'.$request->logo->getClientOriginalExtension();
            $path = './img/sociallogos/'.$logo;
            $request->logo->move('./img/sociallogos', $logo);
            Service::findOrFail($request->input('id'))->update(['logo' => $path , 'status' => false]);
            return redirect()->back();
       }
       else{
            Service::findOrFail($request->input('id'))->update($request->all());
            return redirect()->back();
       }
    }

    public function delete_service(Request $request){
        File::delete(Service::find($request->input('id'))->logo);
        Service::findOrFail($request->input('id'))->delete();
        return redirect()->back();
     }
}
