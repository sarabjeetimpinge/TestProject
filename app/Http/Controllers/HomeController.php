<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function custom_login(Request $request){
        $input = $request->all();
        $email = $input['email'];
        
        /*Getting Base URL*/  
        $url= url('/');
        $url = $url.'/api/validate_user';
        //dd($email);
        $data = array("email" => $email);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                      
            'Content-Length: ' . strlen($data_string))                                        
        );                                                                                                                   
        $result = curl_exec($ch);
        //dd($result);

        if(!empty($result)){
            return redirect()->back()->with('message', 'PLease check your email for verification Link');
        }

          
    }

    public function check_token($token,Request $request){
        
        /*Getting Base URL*/  
        $url= url('/');
        $url = $url.'/api/verify_token/'.$token;
        
        /*cURL*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
         
        $response = curl_exec($ch);
         
        if(curl_error($ch)){
            echo 'Request Error:' . curl_error($ch);
        }
        else
        {
            $response = json_decode($response);
           // dd($response);
            $status = $response->status;
            $userData = $response->userData;
            if($status == 1){
                $user = User::find($userData->id);
                
                /*Making Auth*/
                Auth::login($user);
                $loggedUser = Auth::user();
               
                return view('dashboard')->with('message', 'Welcome to dashboard!')->with('user',$loggedUser);
            }else{
                return abort(404);
            }
        }
         
        curl_close($ch);

    }

    public function editEmail(Request $request){
        $method = $request->method();
        if ($request->isMethod('post')) {
            $email = $request->get('email');
            $user = Auth::user();
            $user['email'] = $email;
            $user->save();

            return redirect()->back()->with('message', 'Email Update Successfully!');
        }else{
            $user = Auth::user();
            return view('editEmail')->with('user',$user);
        }

       
    }

    public function dashboard(){
        return view('dashboard')->with('message', 'Welcome to dashboard!');
    }
}
