<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\User;
use App\sessions;
use Illuminate\Support\Facades\DB;

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
        //loged in users
        
        // Get time session life time from config.
        $time =  time() - (config('session.lifetime')*60); 

        // Total login users (user can be log on 2 devices will show once.)
       // $totalActiveUsers = sessions::where('last_activity','>=', $time)->
        //count(DB::raw('DISTINCT user_id'));

        // Total active sessions
        $totalActiveUsers = 1;

        //dd($totalActiveUsers);

        $users = User::all();
        $user_count = User::count();
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
       
        return view('home',compact('users','totalActiveUsers','user_count','date_array','text_count','image_count','video_count'));
    }

    public function getNotification(Request $request)
    {
        // For the sake of simplicity, assume we have a variable called
        // $notifications with the unread notifications. Each notification
        // have the next properties:
        // icon: An icon for the notification.
        // text: A text for the notification.
        // time: The time since notification was created on the server.
        // At next, we define a hardcoded variable with the explained format,
        // but you can assume this data comes from a database query.
    
        $notifications = [
            [
                'icon' => 'fas fa-fw fa-envelope',
                'text' => rand(0, 10) . ' new messages',
                'time' => rand(0, 10) . ' minutes',
            ],
            [
                'icon' => 'fas fa-fw fa-users text-primary',
                'text' => rand(0, 10) . ' friend requests',
                'time' => rand(0, 60) . ' minutes',
            ],
            [
                'icon' => 'fas fa-fw fa-file text-danger',
                'text' => rand(0, 10) . ' new reports',
                'time' => rand(0, 60) . ' minutes',
            ],
            [
                'icon' => 'fas fa-fw fa-envelope',
                'text' => rand(0, 10) . ' new messages',
                'time' => rand(0, 10) . ' minutes',
            ],
        ];
    
        // Now, we create the notification dropdown main content.
    
        $dropdownHtml = '';
    
        foreach ($notifications as $key => $not) {
            $icon = "<i class='mr-2 {$not['icon']}'></i>";
    
            $time = "<span class='float-right text-muted text-sm'>
                       {$not['time']}
                     </span>";
    
            $dropdownHtml .= "<a href='#' class='dropdown-item'>
                                {$icon}{$not['text']}{$time}
                              </a>";
    
            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }
    
        // Return the new notification data.
    
        return [
            'label'       => count($notifications),
            'label_color' => 'danger',
            'icon_color'  => 'dark',
            'dropdown'    => $dropdownHtml,
        ];
    }
}
