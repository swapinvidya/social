<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use WaleedAhmad\Pinterest\Facade\Pinterest;

class PinterestController extends Controller
{
    public function redirectToPinterestProvider(){
        return Socialite::with('pinterest')->scopes([
            
            'ads:read',
            'boards:read',
            'boards:read_secret',
            'boards:write',
            'boards:write_secret',
            'pins:read',
            'pins:read_secret',
            'pins:write',
            'pins:write_secret',
            'user_accounts:read',
        ])->redirect();
    }
 
    public function handlePinterestProviderCallback(){
        $user = Socialite::driver('pinterest')->user();
        $details = [
            "token" => $user->token
        ];
 
        if(Auth::user()->pinterest){
            Auth::user()->pinterest()->update($details);
        }else{
            Auth::user()->pinterest()->create($details);
        }
        return redirect('/connect');
    }

    public function getAuthUser(){
        $user = Pinterest::user()->me();
        dd($user);
    }

    public function getPins()
    {
        $pins = Pinterest::user()->getMePins();
        dump($pins);
        if($pins->hasNextPage()){
            $more_pins = Pinterest::user()->getMePins([
                'cursor' => $pins->pagination['cursor']
            ]);
            dd($more_pins);
        }
    }

    public function getBoards()
    {
        $boards = Pinterest::user()->getMeBoards();
        dd($boards);
    }

    public function createPin()
    {
        // Create a pin from external source
        Pinterest::pins()->create(array(
            "note"          => "Pin Caption",
            "image_url"     => "https://imgur.com/oSDNUSD",
            "board"         => "waleedahmad/pinterest-laravel"
        ));
    
        // Create a pin from storage path
        Pinterest::pins()->create(array(
            "note"          => "Pin Caption",
            "image"         => Storage::path('/path/to/your/image.jpg'),
            "board"         => "waleedahmad/laravel-pinterest"
        ));
    
        // Create ping with base64 encoded image
        Pinterest::pins()->create(array(
            "note"          => "Pin Caption",
            "image_base64"  => "[base64 encoded image]",
            "board"         => "waleedahmad/laravel-pinterest"
        ));
    }

    public function editPin()
    {
        Pinterest::pins()->edit("181692166190244554", array(
            "note"  => "Update Caption"
        ));
    }

    public function getFollowingUsers()
    {
        $users = Pinterest::following()->users();
        dd($users);
    }

    public function getFollowingBoards()
    {
        $boards = Pinterest::following()->boards();
        dd($boards);
    }

    public function getFollowingInterests()
    {
        $interests = Pinterest::following()->interests();
        dd($interests);
    }
}
