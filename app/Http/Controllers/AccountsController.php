<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use App\Service;
use App\Package;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\ErrorHandler\Error\FatalError;
use App\ayrshare;

use App\FacebookID;
use App\FacebookPage;
use phpDocumentor\Reflection\Types\Null_;


class AccountsController extends Controller
{
    public function connect_account (){

        $packages = Package::find(Auth::user()->package_type);
        $activated_services = $packages->service_in_package->pluck('service_name');
        $services = Service::find($activated_services);

        $fb_group = false;
        $fb_post = false;
        $insta = false;

        $fb_url = "";
        $fbg_url = "";
        $insta_url  = "";




        foreach ($activated_services as $at)
            {
                switch ($at) {
                    case 1:
                        //dd('facebook - Page');
                        if (FacebookID::where('user_id',Auth::id())->exists()){
                            $fb_post = true;
                            $fb_url = "/refresh_fbp";
                        }
                        else{
                            $fb_post = false;
                            $fb_url = "/auth/facebook";
                        }

                    break;

                    case 2:
                       // dd('facebook - groups');
                       $fb_group = false;
                       $fbg_url = "/connect_fbg";
                    break;

                    case 3:
                       // dd('facebook - instagram');
                       $insta = false;
                       $insta_url = "/connect_insta";
                    break;

                    default:
                        //dd('facebook - twitter');
                       // $connection_status = false;
                }
            }

        $connection_status = array(false , $fb_post , $fb_group , $insta);
        $connection_url = array("offset" , $fb_url , $fbg_url , $insta_url);

        
        try {
            //$ayr = ayrshare::where('user_id',Auth::id())->first();
            
            
        } catch (\Exception $e) {
            $packages = Package::first();
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
        }


        return view('client.connect',compact('services','packages','activated_services','connection_status','connection_url'));
    }

    public function create_account_fb (){
        $fb_pages = FacebookPage::where("user_id",Auth::id())->get();
        return view('client.facebook.create_account',compact('fb_pages'));
    }

    public function manage_account (){
        $services = Service::all();
        $packages = Package::all();
        $FacebookID = FacebookID::all();
        $FacebookPage = FacebookPage::all();
        $Account = Account::all();
        return view('client.manage',compact('services','packages','FacebookID','FacebookPage','Account'));
    }

    public function save_account(Request $request){
        $done = Account::create([
                    'user_id' => Auth::id(),
                    'page_id' => $request->input('page_id'),
                    'page_token'=> FacebookPage::find($request->input('page_id'))->page_token,
                    'name' => $request->input('name'),
                ]);

        if ($done){
                return redirect('/home');
            }

        else{

                dd("Something went worng !");
            }
    }

    
}
