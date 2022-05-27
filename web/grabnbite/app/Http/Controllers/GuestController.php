<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Notification;
use App\Mail\NotifyMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Auth;
use Mail;

class GuestController extends Controller
{
    public function index() {

    	return view('index');
    }

    public function registerForm() {

    	return view('register');
    }

    public function save(Request $request) {

    	if($request->ajax()) {

            $vkey = bin2hex(random_bytes(32));
            $email = $request->get('email');

            // check if email exists
            $validator = User::where('email', $email)->first();

            if($validator===null) {

            	$user = new User([
    				'email' => $email,
    				'password' => Hash::make($request->get('password')),
                	'vkey' => $vkey
    			]);

    			if($user->save()) {

	    			$userRole = User::find($user->id);

	    			$role = new Role([
	    				'name' => 'Admin'
	    			]);

	    			$userRole->roles()->save($role);

	                $data = [
	                    'vkey' => $vkey
	                ];

	                Mail::to($email)->send(new NotifyMail($data));
	                
                    $notification = new Notification([

                        'user_id' => 2, //must be the user id of superadmin
                        'type' => 'Registration',
                        'subject' => 'Registration',
                        'content' => 'User ID # '.$user->id.' registered to the system',
                        'date' => Carbon::now()

                    ]);

                    $notification->save();


    				return response()->json(['success'=>1]);
    			}	
            } else {

            	return response()->json(['success'=>0, 'message'=>'Email already exists']);
            }
    	}
    }

    public function login(Request $request) {

    	if($request->ajax()) {

            $remember = $request->has('remember') ? true : false;

            $data = array(
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            );

            if(auth()->attempt($data,$remember)) {

                foreach(Auth::user()->roles as $role) {
                    $roleName = $role->name;
                }

                if($roleName=='Admin' || $roleName=='Superadmin') {

                    return response()->json(['success'=>1, 'verified'=>Auth::user()->is_verified, 'remember'=>$request->get('remember')]);
                } else {
                    return response()->json(['success'=>0, 'message'=>'Invalid credentials']);
                }

            } else {
                return response()->json(['success'=>0, 'message'=>'Invalid credentials']);
            }
        }       
    }

    public function verify($vkey) {

        $user = User::where('vkey', $vkey)->first();

        if($user!==null) {

            $user->is_verified = 1;
            $user->verified_at = Carbon::now();

            if($user->save()) {

                return view('thankyou');
            }
        }
    }
}
