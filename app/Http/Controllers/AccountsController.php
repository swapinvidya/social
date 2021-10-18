<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Package;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\ErrorHandler\Error\FatalError;

class AccountsController extends Controller
{
    public function connect_account (){
        try {
            $packages = Package::find(Auth::user()->package_type);
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
        } catch (\Exception $e) {
            $packages = Package::first();
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
        }

        return view('client.connect',compact('services','packages','activated_services'));
    }

    public function manage_account (){
        $services = Service::all();
        $packages = Package::all();
        return view('client.manage',compact('services','packages'));
    }
}
