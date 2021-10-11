<?php

namespace App\Http\Controllers;

use App\Package;
use App\Service;
use App\service_in_package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
  public function index(){
      $packages = Package::all();
      $services = Service::where('status',true)->get();
      return view('admin.package',compact("packages","services"));

  }

  public function add(Request $request){
    //dd($request);
    $done = Package::create($request->all());
    $id = $done->id;
    $service_array = $request->input('service');
    for ($x = 0; $x < count($service_array); $x++){
      service_in_package::create([
        'package_id' => $id, 
        'service_name' => $service_array[$x]
      ]);
    }


    
    return redirect()->back();
  }

  public function edit_service(Request $request){
    //dd($request);
    Package::findOrFail($request->input('id'))->update($request->all());
    return redirect()->back();
  }

  public function delete_service(Request $request){
    Package::findOrFail($request->input('id'))->delete();
    return redirect()->back();
  }
}
