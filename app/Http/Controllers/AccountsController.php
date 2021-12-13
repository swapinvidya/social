<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use App\Service;
use App\Package;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\ErrorHandler\Error\FatalError;
use App\ayrshare;
use App\facebook_group;
use App\FacebookID;
use App\FacebookPage;
use App\ProfileQuota;
use App\Twitter;
use App\Pinterst;

use phpDocumentor\Reflection\Types\Null_;


class AccountsController extends Controller
{
    public function connect_account (){

        $packages = Package::find(Auth::user()->package_type);
        $activated_services = $packages->service_in_package->pluck('service_name');
        $services = Service::find($activated_services);

        //set qouta
        if (!ProfileQuota::where('user_id',Auth::id())->exists()){
            ProfileQuota::create([
                'user_id' => Auth::id(),
                'used_qouta' => 0,
                'flag' => 0
            ]);
        }

        $fb_group = false;
        $fb_id =[];
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
                            $fb_id = FacebookID::where('user_id',Auth::id())->pluck('id','name')->toArray();
                            //$fb_id = array_values($fb_id);
                            $fb_url = "/fbp_refresh";
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

                    case 4:
                        // dd('Twitter');
                        if (Twitter::where('user_id',Auth::id())->exists()){
                            $twitter = true;
                            $twitter_url = "/twitter/login";
                        }
                        else{
                            $twitter = false;
                            $twitter_url = "/twitter/login";
                        }
                    break;

                    case 5:
                        // dd('Pinterst');
                        if (Pinterst::where('user_id',Auth::id())->exists()){
                            $pinterst = true;
                            $pinterst_url = "/pinterst/login";
                        }
                        else{
                            $pinterst = false;
                            $pinterst_url = "/pinterst/login";
                        }
                    break;

                    default:
                        //dd('facebook - twitter');
                       // $connection_status = false;
                }
            }

        $connection_status = array(false , $fb_post , $fb_group , $insta ,$twitter,$pinterst);
        $connection_url = array("offset" , $fb_url , $fbg_url , $insta_url,$twitter_url,$pinterst_url);
        
        $usr_pkg = Auth::user()->package_type;
        $Ac_qouta_total = Package::Find($usr_pkg)->accounts_no;
        $Ac_qouta_used = ProfileQuota::where('user_id',Auth::id())->first()->used_qouta;
        $Ac_qouta_avilable = $Ac_qouta_total - $Ac_qouta_used;


        
        try {
            //$ayr = ayrshare::where('user_id',Auth::id())->first();
            
            
        } catch (\Exception $e) {
            $packages = Package::first();
            $activated_services = $packages->service_in_package->pluck('service_name');
            $services = Service::find($activated_services);
        }

        $fb_pages = FacebookPage::where("user_id",Auth::id())->get();
        $total_page_count = $fb_pages->count();
        $maped_page_count = Account::where('user_id',Auth::id())->where('provider','facebook')->count();
        $balance_page = $total_page_count - $maped_page_count;


        return view('client.connect',compact('services','packages','activated_services','connection_status','connection_url','Ac_qouta_total',
        'Ac_qouta_used', 'Ac_qouta_avilable','balance_page','fb_id'));
    }

    public function create_account_fb (){
        $fb_pages = FacebookPage::where("user_id",Auth::id())->get();
        $fb_groups = facebook_group::where("user_id",Auth::id())->get();
        $Account = Account::where('user_id',Auth::id())->pluck('page_id');
        $total_page_count = $fb_pages->count();
        $maped_page_count = Account::where('user_id',Auth::id())->where('provider','facebook')->count();
        $balance_page = $total_page_count - $maped_page_count;
        return view('client.facebook.create_account',compact('fb_pages','fb_groups','Account','balance_page'));
    }

    public function manage_account (){
        $services = Service::all();
        $packages = Package::all();
        $FacebookID = FacebookID::where('user_id',Auth::id())->get();
        $FacebookPage = FacebookPage::where('user_id',Auth::id())->get();
        $Account = Account::where('user_id',Auth::id())->get();

        //Qoutas
        //find package in force
        $usr_pkg = Auth::user()->package_type;
        $Ac_qouta_total = Package::Find($usr_pkg)->accounts_no;
        $Ac_qouta_used = ProfileQuota::where('user_id',Auth::id())->first()->used_qouta;
        $Ac_qouta_avilable = $Ac_qouta_total - $Ac_qouta_used;

        if ($Ac_qouta_total <= $Ac_qouta_used){
            session(['ac_qouta_flag' => true]);
        }

       
                
        foreach ($Account as $ac) {
            Account::find($ac->id)->update([
            'page_token'=> FacebookPage::find($ac->page_id)->page_token,
            ]);
        }

        $fb_pages = FacebookPage::where("user_id",Auth::id())->get();
        $total_page_count = $fb_pages->count();
        $maped_page_count = Account::where('user_id',Auth::id())->where('provider','facebook')->count();
        $balance_page = $total_page_count - $maped_page_count;
        return view('client.manage',compact('services','packages','FacebookID','FacebookPage','Account','Ac_qouta_total',
        'Ac_qouta_used', 'Ac_qouta_avilable','balance_page'));
    }

    public function save_account(Request $request){
        $usr_pkg = Auth::user()->package_type;
        $Ac_qouta_total = Package::Find($usr_pkg)->accounts_no;
        $Ac_qouta_used = ProfileQuota::where('user_id',Auth::id())->first()->used_qouta;
        $Ac_qouta_avilable = $Ac_qouta_total - $Ac_qouta_used;

        if ($Ac_qouta_total <= $Ac_qouta_used){
            session(['ac_qouta_flag' => true]);
            return redirect()->back();
        }

        
        $done = Account::create([
                    'user_id' => Auth::id(),
                    'page_id' => $request->input('page_id'),
                    'page_token'=> FacebookPage::find($request->input('page_id'))->page_token,
                    'name' => $request->input('name'),
                    'provider' => $request->input('provider'),
                ]);

        if ($done){

                $q = ProfileQuota::updateOrCreate(
                        ['user_id' => Auth::id()],
                        ['user_id' => Auth::id()]
                    )->increment('used_qouta');

                //refresh exsisting tokens

                $Account = Account::where('user_id',Auth::id())->get();
                
                foreach ($Account as $ac) {
                   Account::find($ac->id)->update([
                    'page_token'=> FacebookPage::find($ac->page_id)->page_token,
                   ]);
                }


                return redirect('/manage');
            }

        else{

                dd("Something went worng !");
            }
    }

    
}
