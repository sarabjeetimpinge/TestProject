<?php

namespace App\Http\Controllers\Api;

use Mail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
	public function validate_user(Request $request)
    {
        //dd($request->all());
        $email = $request->get('email');
        $userDetail = User::where('email',$email)->first();
        
       /*Creating OAuth2 Token using Passport*/
		$tokenResult = $userDetail->createToken('Personal Access Token');
        $token = $tokenResult->token;
        
        $userDetail['remember_token'] = $token->id;
        $userDetail->save();
        
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addHours(2);
            $token->save();

        $userDetail = User::where('email',$email)->first()->toArray();
        // dd($userDetail);
        $url= url('/');
        
        $link= $url.'/check_token/'.$token->id;

       	/*Sent an email using PostMark Server Key*/
        // dd($userDetail['email']);
       	$sent =  Mail::send('emails/verify_user',['userDetail' => $userDetail,'link'=>$link], function($message) use($userDetail) {
	         $message->to($userDetail['email'], 'Test')->subject
	            ('Verify User');
	         $message->from('test.impinge@impingesolutions.com','sarbjeet');
	      });

        $result = array(
            'message' => 'Email sent Successfully!',
            'user_data' => $userDetail,
            'success' => true,
            'status' => 1,
        );

        return $result;
        
    }

    public function verify_token($token){
        
        $getUser = User::where('remember_token',$token)->first();
        if(!empty($getUser)){
            $getUser['remember_token'] = '';
            $getUser->save();

            $result = array(
                'message' => 'User Verified!',
                'userData' => $getUser,
                'status' => 1
            );
            return $result;
        }else{
            $result = array(
                'message' => 'Something went wrong!',
                'userData' => '',
                'status' => 0
             );
            return $result;

        }
    }
}