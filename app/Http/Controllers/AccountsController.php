<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Package;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\ErrorHandler\Error\FatalError;
use App\ayrshare;
use phpDocumentor\Reflection\Types\Null_;

class AccountsController extends Controller
{
    public function connect_account (){
        
        try {
            $ayr = ayrshare::where('user_id',Auth::id())->first();
            $packages = Package::find(Auth::user()->package_type);
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
            if ($ayr == Null){$ayr_cnt = false;} else {$ayr_cnt = true;}
        } catch (\Exception $e) {
            $packages = Package::first();
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
            $ayr_cnt = false;
        }

        return view('client.connect',compact('services','packages','activated_services','ayr_cnt'));
    }

    public function manage_account (){
        $services = Service::all();
        $packages = Package::all();
        return view('client.manage',compact('services','packages'));
    }
}
