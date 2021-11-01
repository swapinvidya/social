<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\ayrshare;
use App\Package;
use App\Service;
use PhpParser\Node\Stmt\Catch_;
use App\FacebookID;
use App\FacebookPage;
use App\Logic\Providers\FacebookRepository;

class PostController extends Controller
{
    public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }
   
    public function index(){
        $accounts = Account::where('user_id',Auth::id())->get();
        $post = post::where('user_id',Auth::id())->get();
        return view('client.posts.post',compact('post','accounts'));
    }




    public function post(Request $request){
        //dd($request);
        //Get user details

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
                "user_id" => Auth::id(),
                'response' => $response,
                'schedule' => false,
                'file' => $path,
                'shorten' => $shortUrl,
                'media_url' => $page_token,  //page token is passed here for convince
                'media_type' => $media_t
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
    public function test(){     
       dd("SERVICE NOT ACTIVE IN DEMO!!");
    }
}
