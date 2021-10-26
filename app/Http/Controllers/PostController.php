<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function index(){
        return view('client.posts.post');
    }

    public function post(Request $request){
        $post = strip_tags($request->input('teConfig'));
        

        $response = Http::withHeaders([
            'Authorization' => 'Bearer 37GVMEW-WK9M8JK-KPX0GH2-TZQABRP',
            'Content-Type => application/x-www-form-urlencoded'
            ])->asForm()->post('https://app.ayrshare.com/api/post', [
            'post' => 'Sara',
            'platform' => 'facebook',
            ]);
        
            dd($response);


    }
}
