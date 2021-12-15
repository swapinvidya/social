<?php

namespace App\Http\Controllers;

use App\Account;
use App\FacebookID;
use App\FacebookPage;
use App\facebook_group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Logic\Providers\FacebookRepository;
use App\ProfileQuota;

class SocialController extends Controller
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }

    public function redirectToProvider()
    {
        return redirect($this->facebook->redirectTo());
    }

    public function handleProviderCallback()
    {
        if (request('error') == 'access_denied') {dd('error');}
            //handle error

        $accessToken = $this->facebook->handleCallback(); 
        
        $me = $this->facebook->getMe($accessToken);
        //use token to get facebook pages
        $f_id = FacebookID::updateOrCreate([
            'fid' => $me['id']
            ],
            [ 
                'user_id' => Auth::id(),
                'fb_token' => $accessToken,
                'name' => $me['name'],
            ]);

        

        $d = FacebookID::find($f_id->id)->fb_token;
        
        $page = $this->facebook->getPages($d);

        $count = count($page);

        for ($i=0; $i < $count ; $i++) { 
            //check for instagram
          
            FacebookPage::create([
                'user_id' => Auth::id(),
                'token_id' => $f_id->id,
                'page_id' => $page[$i]['id'],
                'page_token' => $page[$i]['access_token'],
                'image'=> $page[$i]['image'],
                'name' => $page[$i]['name'],
                'provider' => $page[$i]['provider'],
            ]);
        }



        $group = $this->facebook->getgroups($d);
        $count = count($group);
        for ($i=0; $i < $count ; $i++) { 
            facebook_group::create([
                'user_id' => Auth::id(),
                'token_id' => $f_id->id,
                'group_id' => $group[$i]['id'],
                'group_token' => "NA",
                'image' => "NA",
                'name' => $group[$i]['name'],
                'privacy' => $group[$i]['privacy'],
                'provider' => $group[$i]['provider'],
            ]);
        }

        return redirect('/create_account_fb');
    }

    public function fbp_refresh(Request $request){

        //dd($request);
       
        $d = FacebookID::find($request->input('id'))->fb_token;

        $id = $request->input('id');


        $page = $this->facebook->getPages($d);
        $group = $this->facebook->getGroups($d);
        
        $count = count($page);
        $g_count = count($group);

        $existing_page = FacebookPage::where('user_id',Auth::id())->count();
        $existing_group = facebook_group::where('user_id',Auth::id())->count();

        $existing_page_id_array = FacebookPage::where('user_id',Auth::id())->pluck('page_id')->toArray();

        $no_of_new_pages = $count - $existing_page;

        //dd($no_of_new_pages);
        

        if ($count > $existing_page){
                //dd ($page);
                //check page id to exlude exisiting pages its logic
                if ($existing_page == 0){
                    for ($i=0; $i < $count; $i++) { 
                        FacebookPage::create([
                            'user_id' => Auth::id(),
                            'token_id' => $id,
                            'page_id' => $page[$i]['id'],
                            'page_token' => $page[$i]['access_token'],
                            'image'=> $page[$i]['image'],
                            'name' => $page[$i]['name'],
                            'provider' => $page[$i]['provider'],
                        ]);
                    }
                    
                }else{
                    for ($i=0; $i < $count; $i++) { 
                        $flight = FacebookPage::updateOrCreate(
                            ['page_id' => $page[$i]['id']],
                            [
                                'user_id' => Auth::id(),
                                'token_id' => $id,
                                'page_id' => $page[$i]['id'],
                                'page_token' => $page[$i]['access_token'],
                                'image'=> $page[$i]['image'],
                                'name' => $page[$i]['name'],
                                'provider' => $page[$i]['provider'],
                            ]
                        );
                    }
                }

                if ($existing_group == 0){
                    for ($i=0; $i < $g_count ; $i++) { 
                        facebook_group::create([
                            'user_id' => Auth::id(),
                            'token_id' => $id,
                            'group_id' => $group[$i]['id'],
                            'group_token' => "NA",
                            'image' => "NA",
                            'name' => $group[$i]['name'],
                            'privacy' => $group[$i]['privacy'],
                            'provider' => $group[$i]['provider'],
                        ]);
                    }
                }else{
                    for ($i=0; $i < $g_count; $i++) { 
                        $flight = FacebookPage::updateOrCreate(
                            ['group_id' => $group[$i]['id']],
                            [
                                'user_id' => Auth::id(),
                                'token_id' => $id,
                                'group_id' => $group[$i]['id'],
                                'group_token' => "NA",
                                'image' => "NA",
                                'name' => $group[$i]['name'],
                                'privacy' => $group[$i]['privacy'],
                                'provider' => $group[$i]['provider'],
                            ]
                        );
                    }

                }
            
                return redirect('/create_account_fb');

            }
            

        else {

                //dd("No new page Found");
                for ($i=0; $i < $count ; $i++) { 

                    FacebookPage::where('page_id',$page[$i]['id'])->first()->update(
                        [
                            'user_id' => Auth::id(),
                            'token_id' => $id,
                            'page_id' => $page[$i]['id'],
                            'page_token' => $page[$i]['access_token'],
                            'image'=> $page[$i]['image'],
                            'name' => $page[$i]['name'],
                            'provider' => $page[$i]['provider'],
                        ]
                    );

                }

                for ($i=0; $i < $g_count ; $i++) { 
                    facebook_group::where('group_id',$group[$i]['id'])->first()->update([
                        'user_id' => Auth::id(),
                        'token_id' => $id,
                        'group_token' => "NA",
                        'image' => "NA",
                        'name' => $group[$i]['name'],
                        'privacy' => $group[$i]['privacy'],
                        'provider' => $group[$i]['provider'],
                    ]);
                }


            }


        return redirect('/create_account_fb');
    }

    public function instagram_connect(){
        //find out how many Accounts are configured by particular user
        //$registred_accounts = FacebookID::where('user_id',Auth::id())->get();
        $registred_accounts = FacebookID::where('id',2)->get();
        $registerd_accounts_count = $registred_accounts->count();
        $pages_maped = FacebookPage::where('user_id',Auth::id())->get();

        $array_ids = FacebookPage::where('user_id',Auth::id())->pluck('id')->toArray();

       foreach ($registred_accounts as $ra) { 
           $token = $ra->fb_token;
           //update pages
           $page = $this->facebook->getPages($token);
           for ($j=0; $j < count($page); $j++) { 
                $flight = FacebookPage::updateOrCreate(
                    ['page_id' => $page[$j]['id']],
                    [
                        'user_id' => Auth::id(),
                        'token_id' => $ra->id,
                        'page_id' => $page[$j]['id'],
                        'page_token' => $page[$j]['access_token'],
                        'image'=> $page[$j]['image'],
                        'name' => $page[$j]['name'],
                        'provider' => $page[$j]['provider'],
                    ]
                );
            }

            foreach ( $pages_maped as $pm) {
            //check if instaaccount is prestent or not
                $p_id = $pm->page_id;
                $response = $this->facebook->getInsta($p_id,$token);

                for ($i=0; $i < count($response); $i++) { 
                    //update instagram details
                    if (FacebookPage::where('page_id',$response[$i]['id']) != null){
                        FacebookPage::where('page_id',$response[$i]['id'])->first()->update([
                            'present' => $response[$i]['instagram'],
                            'instagarm_id'  =>$response[$i]['instagram_id'],
                        ]);
                    }
                    else{
                        dd("Fatal Error");
                    }
                }
            
            }
            
       }

       //update other insta detals
       $insta_accounts = FacebookPage::where('user_id',Auth::id())
                            ->where('present',true)->get();
        foreach($insta_accounts as $ia){
            $insta_id = FacebookPage::find($ia->id)->instagarm_id;
            $accessToken = FacebookID::find(FacebookPage::find($ia->id)->token_id)->fb_token;
            $result = getInstabasic($insta_id,$accessToken);
            /*
            FacebookPage::find($ia->id)->update([
                'profile_picture_url',
                'instagram_name'
            ]);
            */
        }

       return redirect()->back();

    }

   
}