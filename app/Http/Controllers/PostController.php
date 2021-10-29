<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\ayrshare;
use App\Package;
use App\Service;
use PhpParser\Node\Stmt\Catch_;

class PostController extends Controller
{
    public function index(){
        $post = post::where('user_id',Auth::id())->get();
        return view('client.posts.post',compact('post'));
    }




    public function post(Request $request){
        //dd($request);
        //Get user details
        
        $user_package_type = Auth::user()->package_type;
        try{
                $user_service_ids = Package::find($user_package_type)->service_in_package->pluck('service_name');
                $user_service_names = Service::whereIn('id', $user_service_ids)->pluck('name')->toArray();
            }
        catch (\Exception $e){
                dd("CONFIG ERROR PACKAGE NOT SET");
            }

        if (in_array("Ankit", $user_service_names)){
            
            //key
            $s_id = Service::where('name','Ayrshare')->first()->id;
            $api = Service::find($s_id)->api_key;
            $bearer = 'Authorization: Bearer '.$api; 

            //check if short url is checked
            if ($request->input('shorturl') == "on"){$shortUrl = true;} else {$shortUrl = false;}

            //check if file is uploded
            if ($request->hasFile('file')){$hasFile = true;} else{$hasFile = false;}


            //Store file to storgae
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

            //cerate url

            $media ="https://picsum.photos/200/300";



            //remove html tags
            $post = strip_tags($request->input('teConfig'));

            // create postfileds
            //get avilable platforms
            $regDetails = ayrshare::where('user_id',Auth::id())->first();
            $res = json_decode($regDetails->response);
            $platformArray = $res->activeSocialAccounts;
            $platform = implode("&platforms%5B0%5D=",$platformArray);

            
            
            $post_field = 'post='.$post.'&platforms%5B0%5D='.$platform.'&mediaUrls%5B0%5D='.$media.'&scheduleDate=&shortenLinks=';


            //API call 
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.ayrshare.com/api/post',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_field,
                CURLOPT_HTTPHEADER => array(
                        $bearer,
                        'Content-Type: application/x-www-form-urlencoded'
                        ),
                ));
            
            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            
            
            Post::create([
                "post" => $post,
                "user_id" => Auth::id(),
                'response' => $response,
                'schedule' => false,
                'file' => $path,
                'shorten' => false,
                'media_url' => $media,
                'media_type' => $media_t
            ]);

            if ($info['http_code'] == 200)
                {

                    $request->session()->flash('message', 'Posted Sucessfully');
                    $request->session()->flash('type', 'success');
                    $request->session()->flash('icon', 'check');
                    return redirect()->back();
                }
            else{
                    try{
                            $msg = json_decode($response)->errors[0]->message;
                        }
                    catch (\Exception $e)
                        {
                            $msg = "Unknown API Error!";
                        }
                
                    $request->session()->flash('message', $msg);
                    $request->session()->flash('type', 'danger');
                    $request->session()->flash('icon', 'ban');
                    return redirect()->back();
                }
        }
        else{

            dd($request, "SERVICE NOT ACTIVE IN DEMO");
        }



    }

    public function test(){     
       dd("SERVICE NOT ACTIVE IN DEMO!!");
    }
}
