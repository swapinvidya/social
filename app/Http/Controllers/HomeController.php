<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {
        //data for chart
        $today = Carbon::now();
        $date_array = array();
        $date_count = array();

        $i = 0;
        while ($i < 7) {
            array_push( $date_array, $today->subDays($i)->format('M-d') );
            $i++;
        }
        
        $text_count=array(12,15,19,25,28,30,27);
        $image_count=array(7,15,5,10,14,10,25);
        $video_count=array(20,15,15,20,20,17,10);
       
        return view('home',compact('date_array','text_count','image_count','video_count'));
    }
}
