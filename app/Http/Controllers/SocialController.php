<?php

namespace App\Http\Controllers;

use App\FacebookID;
use App\FacebookPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Logic\Providers\FacebookRepository;

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
        
        //use token to get facebook pages
        $f_id = FacebookID::create([
            'user_id' => Auth::id(),
            'fb_token' => $accessToken
        ]);

        $d = FacebookID::find($f_id->id)->fb_token;
        $page = $this->facebook->getPages($d);

        $count = count($page);

        for ($i=0; $i < $count ; $i++) { 
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
        return redirect('/create_account_fb');
    }
}