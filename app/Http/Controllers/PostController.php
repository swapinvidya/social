<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\ayrshare;
use App\facebook_group;
use App\Package;
use App\Service;
use PhpParser\Node\Stmt\Catch_;
use App\FacebookID;
use App\FacebookPage;
use App\Logic\Providers\FacebookRepository;
use App\Pinterest;
use Atymic\Twitter\Facade\Twitter as FacadeTwitter;
use App\Twitter;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }
   
    public function index(){
        $accounts = Account::where('user_id',Auth::id())->get();
        $post_batch = post::where('user_id',Auth::id())->get()->unique('post_id');
        $post = post::where('user_id',Auth::id())->get();
        $package = Package::find(Auth::user()->package_type)->service_in_package->pluck('service_name')->toArray();
        $service = Service::all();
        // dd($post);
        return view('client.posts.post',compact('post','post_batch','accounts','package','service'));
    }

    public function post(Request $request){

        //privent similar text from posting
        $lastpost = Post::where('user_id',Auth::id())->latest()->get()[0]->post;
        similar_text(strtoupper(strip_tags($request->input('teConfig'))),strtoupper($lastpost),$percent);
        if($percent > 60){
            dd("cannot post similar text");
        }

        //allot batch id
        $bacth_id = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

        //where to post
        $array_account_id = $request->input('accounts');
        $user_accouts = Account::where('user_id',Auth::id())->get();

        /*providers 
            0 => "facebook"
            3 => "Instagram"
            4 => "facebook group"
            5 => "Pinterest"
            6 => "Pinterest"
            7 => "LinkedIn"
            8 => "Twitter"
        */

        //check if file is uploded
        if ($request->hasFile('file')){$hasFile = true;} else{$hasFile = false;}

        if ($hasFile){

            $file = time().'.'.$request->file->getClientOriginalExtension();
            $path = './post_img/'.$file;
            $request->file->move('./post_img/', $file);
            $media_t="image";
            
        }
        else {
            $path = "";
            $media_t = "text";
        }

        foreach($array_account_id as $id){
            $provider = $user_accouts->find($id)->provider;
            $fafa = $user_accouts->find($id)->fa_fa;
            

            switch ($provider) {
                /* This is code is protected
                    (c) Swapin vidya; All rights reserved  
                */
                case "facebook":

                    //page toke
                    $page_token = Account::find($id)->page_token;
                    //page_id
                    $page_id = FacebookPage::where('page_token',$page_token)->first()->page_id;

                    $post = strip_tags($request->input('teConfig'));

                    $img = array($path);

                    //post to facebook
                    $a = $this->facebook->post($page_id, $page_token, $post, $img);

                    if ($a) {

                        $b = array('status' => "success", 'reason' => "Posted Successfully", 'post' => $post);
                        $response = json_encode(array_merge(json_decode($a, true),$b));
                        $post_id = json_decode($a)->id;
                        $status = "success";
                
                    } else{
                            
                        $b = array('status' => "Error", 'reason' => "API Error" , 'post' => $post);
                        $response = json_encode($b);
                        $post_id = "NA";
                        $status = "failed";
                    }

                 

                    Post::create([
                            "user_id" => Auth::id(),
                            "post" => $post,
                            "response" => $response,
                            "schedule" => false,
                            "file" => implode(",", $img),
                            "shorten" => false,
                            "media_url" => false,
                            "media_type" => $media_t,
                            "provider" => $provider,
                            "fa_icon" => $fafa,  
                            "account_id" => $id,
                            "status" => $status,
                            "post_id"=> $bacth_id,
                            "post_id_str" =>$post_id,
                            "page_token" =>"",
                        ]);
                    break;

                case "facebook group":
                    //page toke
                    $page_token = Account::find($id)->page_token;
                    $groupName = Account::find($id)->name;
                    //page_id
                    $page_id = FacebookPage::where('page_token',$page_token)->first()->page_id;
                    
                    $groupId = facebook_group::where('user_id',Auth::id())
                                ->where('provider','facebook group')
                                ->where('name',$groupName)
                                ->first()->group_id;

                    $post = strip_tags($request->input('teConfig'));

                    $img = array($path);
                    $a = $this->facebook->postGroup($page_token,$groupId,$post);
                    Post::create([
                        "user_id" => Auth::id(),
                        "post" => $post,
                        "response" => $a,
                        "schedule" => false,
                        "file" => implode(",", $img),
                        "shorten" => false,
                        "media_url" => false,
                        "media_type" => $media_t,
                        "provider" => $provider,
                        "fa_icon" => $fafa,  
                        "account_id" => $id,
                        "status" => 'Failed',
                        "post_id"=> $bacth_id,
                        "post_id_str" =>"NA",
                        "page_token" =>"",
                    ]);

                    break;
                case   "Instagram":
                    //
                    break;
                case   "Pinterest":
                        $page_id = Account::find($id);
                        $pinterest = Pinterest::find($page_id->page_id);
                        $pintrest_token = $pinterest->token;
                       // dd($pintrest_token);
                        $post = strip_tags($request->input('teConfig'));
                        $img = array($path);

                        /* create the board and get id */
                        $payload = json_encode(array(
                            "name" => $post,
                           // "description" => $post,
                            "privacy" => "PUBLIC"
                        ),JSON_FORCE_OBJECT);

                        //dd($payload);

                        $curl_header = array(
                            'Authorization: Bearer '.$pintrest_token,
                            'Content-Type: application/json',
                            'Cookie: _auth=0; _pinterest_sess=TWc9PSZvTE5TY2VhYVF3MTY5TVNNY2JRekIraEtabERkWmRyOVhTSTBJSnFvUWJyVWU1dUt2UytUbGpLc1JaMnN5MGlzZmRsNUtZTE40dmVKMVczMm90WkpMdW90OXY0MExrZVRtNUxBT3lmS1FiUjN1QmRpZ1JzNzZ2NnROaXVNTW8xLyZVcjRiOHhLK0pCNHY4c1cvYk9aNVlSdXZNR3M9; _ir=0'
                        );

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.pinterest.com/v5/boards',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => $curl_header,
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        if (json_decode($response) != null){
                            $board_id = json_decode($response)->id;
                        }
                        else{
                            dd("Fatal error", $response);
                        }
                        
                        //dd($response);
                        /** BOARD CREATED */

                        $imagedata = file_get_contents($path);
                                    // alternatively specify an URL, if PHP settings allow
                       // $base64 = base64_encode($imagedata);
                        $data = base64_encode($imagedata);
                        $ms = array(
                                "source_type" => "image_base64",
                                "content_type" => "image/jpeg",
                                "data" => $data 
                            );
                        $pf = array(
                            "title" => $post,
                            // "description" => "string",
                            // "alt_text" => "string",
                            "board_id" => $board_id,
                            "media_source" => $ms );
                        $postfield = json_encode($pf);

                       

                       // dd($postfield);
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.pinterest.com/v5/pins',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $postfield,
                        CURLOPT_HTTPHEADER => $curl_header,
                        ));

                        $a = curl_exec($curl);

                        curl_close($curl);

                        $r = json_decode($a,true);

                        if (isset($r["id"])){

                            $b = array('status' => "success", 'reason' => "Posted Successfully", 'post' => $post);
                            $response = json_encode(array_merge(json_decode($a, true),$b));
                            $post_id = json_decode($a)->id;
                            $status = "success";
                    
                        } else{
                                
                            $b = array('status' => "Error", 'reason' => "API Error" , 'post' => $post);
                            $response = json_encode($b);
                            $post_id = "NA";
                            $status = "failed";
                        }

                        Post::create([
                            "user_id" => Auth::id(),
                            "post" => $post,
                            "response" => $response,
                            "schedule" => false,
                            "file" => implode(",", $img),
                            "shorten" => false,
                            "media_url" => false,
                            "media_type" => $media_t,
                            "provider" => $provider,
                            "fa_icon" => $fafa,  
                            "account_id" => $id,
                            "status" => $status,
                            "post_id"=> $bacth_id,
                            "post_id_str" => $post_id,
                            "page_token" =>"",
                        ]);

                    break;
                case   "LinkedIn":
                    //dd('Linkedin');
                    break;  
                case   "Twitter":
                    //dd('Twitter');
                    break;
                default:
                  dd("provider not configured");
              } 
            
        }

        dd($user_accouts->pluck('provider'));

       
        return redirect()->back();

    }


    public function post_old(Request $request){
        dd($request);
        //Get user details
        //check connections
        //if facebook
        if (FacebookID::where('user_id',Auth::id())->exists()){$fb = true;} else{$fb=false;}
        //if twitter
        if (Twitter::where('user_id',Auth::id())->exists()){$tw = true;} else{$tw=false;}

        //dd($request);

        if ($request->input('shorturl') == "on"){$shortUrl = true;} else {$shortUrl = false;}

        //check if file is uploded
        if ($request->hasFile('file')){$hasFile = true;} else{$hasFile = false;}

        if ($hasFile){

            $file = time().'.'.$request->file->getClientOriginalExtension();
            $path = './post_img/'.$file;
            $request->file->move('./post_img/', $file);
            $media_t="image";
            
        }
        else {
            $path = "";
            $media_t = "text";
        }

        



        
        
        foreach ($request->input('accounts') as $ac) {

           if($fb) {
            //page toke
            $page_token = Account::find($ac)->page_token;
            //page_id
            $page_id = FacebookPage::where('page_token',$page_token)->first()->page_id;

            $post = strip_tags($request->input('teConfig'));

            $img = array($path);

            //dd( $page_token , $page_id ,  $post , $img);
                
            $a = $this->facebook->post($page_id, $page_token, $post, $img);
            

                if ($a) {

                    $b = array('status' => "success", 'reason' => "Posted Successfully", 'post' => $post);
                    $response = json_encode(array_merge(json_decode($a, true),$b));
                    $post_id = json_decode($a)->id;
            
                } else{
                        
                    $b = array('status' => "Error", 'reason' => "API Error" , 'post' => $post);
                    $response = json_encode($b);
                    $post_id = "NA";
                }

                Post::create([
                    "post" => $post_id,
                    "post_text" => $post,
                    "user_id" => Auth::id(),
                    'response' => $response,
                    'schedule' => false,
                    'file' => $path,
                    'shorten' => $shortUrl,
                    'media_url' => $page_token,  //page token is passed here for convince
                    'media_type' => $media_t,
                    'provider' => 'facebook' //need to cahnge when multiple accounts
                ]);
            }
            
           
        }

        if($tw){

            $oauth_token = Twitter::where('user_id',Auth::id())->first()->oauth_token; 
            $oauth_token_secret = Twitter::where('user_id',Auth::id())->first()->oauth_token_secret;
            
            $twitter = FacadeTwitter::usingCredentials($oauth_token, $oauth_token_secret);
            if ($hasFile)
            {
                //dd("$path");
                $uploaded_media = $twitter->uploadMedia(['media_data' => base64_encode(file_get_contents($path))]);
            
                $tw_response = $twitter->postTweet(['status' => $post, 'media_ids' => $uploaded_media->media_id_string ,  'response_format' => 'json']);

               
            }
           else{
                $tw_response = $twitter->postTweet(['status' => $post, 'response_format' => 'json']);
            }

           
            
            
                //dd( $tw_response);
            
                $tw_true = array('status' => "success", 'reason' => "Posted Successfully", 'post' => json_decode($tw_response)->text);
                $tw_false = array('status' => "Error", 'reason' => "API Error" , 'post' => json_decode($tw_response)->text);

                $tw_resp = json_encode($tw_true);


            Post::create([
                "post" => json_decode($tw_response)->id,
                "post_text" => $post,
                "user_id" => Auth::id(),
                'response' => $tw_resp,
                'schedule' => false,
                'file' => $path,
                'shorten' => $shortUrl,
                'media_url' => json_decode($tw_response)->id_str,  //page token is passed here for convince
                'media_type' => $media_t,
                'provider' => 'twitter' //need to cahnge when multiple accounts
            ]);

        }

        if ($a)
            {

                $request->session()->flash('message', 'Posted Sucessfully');
                $request->session()->flash('type', 'success');
                $request->session()->flash('icon', 'check');
                return redirect()->back();
            }
        else{
               
                $msg = "Unknown API Error!";
                $request->session()->flash('message', $msg);
                $request->session()->flash('type', 'danger');
                $request->session()->flash('icon', 'ban');
                return redirect()->back();
            }
        


    }

    public function delete_post(Request $request){
        //dd($request);
        $postIDtoDelete = $request->input('postIDtoDelete');
        $pageAccessToken = $request->input('pageAccessToken');
        $a = $this->facebook->delPost($postIDtoDelete,$pageAccessToken);
        
        if (!$a){
            $msg = "Unknown API Error!";
            $request->session()->flash('message', $msg);
            $request->session()->flash('type', 'danger');
            $request->session()->flash('icon', 'ban');
            return redirect()->back();
        }
        else{
            $request->session()->flash('message', 'Post Deleted Sucessfully');
            $request->session()->flash('type', 'success');
            $request->session()->flash('icon', 'check');
            Post::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }    

    public function image_editor(Request $request){
        return view('image');
    }




    public function test(){     
       dd("SERVICE NOT ACTIVE IN DEMO!!");
    }
}
