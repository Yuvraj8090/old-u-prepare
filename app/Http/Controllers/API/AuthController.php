<?php

namespace App\Http\Controllers\API;

use App\Mail\EMail;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

// use App\Services\Frontend\Auth\VerifyService;
// use App\Services\Frontend\Auth\PasswordService;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Method to Authenticate the User
     *
     * @param Illuminate\Http\Request $request The incoming request
     * @author Robin Tomar <robintomr@icloud.com>
     * @return array
     */
    public function login(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> ''];

        $validator = Validator::make($request->all(), [
            'username'=> 'required',
            'password'=> 'required',
        ]);

        if(!$validator->fails())
        {
            if(auth()->attempt($validator->validated()))
            {
                $user = User::with('role:id,name,level,department,level_name')->where('id', auth()->id())->first();
                $accessToken = $user->createToken('authToken');

                unset($user['user_id'], $user['role_id'], $user['email_otp'], $user['email_verified_at'], $user['otp_sent_at']);

                $scode                  = 200;
                $return['ok']           = 1;
                $return['msg']          = 'You are logged in successfully!';
                $return['user']         = $user;
                $return['access_token'] = $accessToken->plainTextToken;
            }
            else
            {
                $scode         = 422;
                $return['msg'] = 'An invalid username/password is provided!';
            }
        }
        else
        {
            $return['msg'] = $validator->errors()->first();
        }

        return response($return, $scode);
    }


    /**
     *
     */
    public function getUser(Request $request)
    {
        return $request->user();
    }


    /**
     * 
     */
    public function forgot(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> ''];

        if($request->filled('username'))
        {
            $user = User::where('username', $request->username)->first();

            if($user)
            {
                // Send OTP to Registered Email
                $email_otp         = mt_rand(1000, 9999);
                $user->email_otp   = $email_otp;
                $user->otp_sent_at = now();

                $user->save();

                $scode         = 200;
                $return['ok']  = 1;
                $return['otp'] = $email_otp;
                $return['msg'] = 'OTP is sent to your registered email address';
            }
            else
            {
                $scode         = 422;
                $return['msg'] = 'An invalid username is provided!';
            }
        }
        else
        {
            $return['msg'] = 'Please provide your username to continue!';
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function reset(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> ''];

        if($request->filled('username', 'otp', 'password', 'confirm_password'))
        {
            $scode = 422;

            if($request->password === $request->confirm_password)
            {
                $user = User::where('username', $request->username)->first();

                if($user && ($request->otp == $user->email_otp))
                {
                    $user->email_otp   = NULL;
                    $user->password    = Hash::make($request->password);
                    $user->otp_sent_at = NULL;

                    if($user->save())
                    {
                        $scode        = 200;
                        $return['ok'] = 1;
                        $return['msg'] = 'Password changed successfully!';
                    }
                    else
                    {
                        $return['msg'] = 'Failed to save the password this time. Please try again!';
                    }
                }
                else
                {
                    $return['msg'] = 'An invalid request is detected!!!';
                }
            }
            else
            {
                $return['msg'] = 'Password and Confirmation Password should match!';
            }
        }
        else
        {
            $return['msg'] = 'An invalid request is detected!';
        }

        return $return;
    }


    /**
     * 
     */
    public function verifyOTP(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected.'];

        if($request->filled('username', 'otp'))
        {
            $user = User::where('username', $request->username)->first();

            if($user)
            {
                if($request->otp == $user->email_otp)
                {
                    $scode         = 200;
                    $return['ok']  = 1;
                    $return['msg'] = 'OTP Verified Successfully!';
                }
                else
                {
                    $return['msg'] = 'OTP Verification Failed!';
                }
            }
        }

        return response($return, $scode);
    }


    /**
     * 
     */
    public function resendOTP(Request $request)
    {
        $scode  = 400;
        $return = ['ok'=> 0, 'msg'=> 'An invalid request is detected'];

        if($request->filled('username'))
        {
            $user = User::where('username', $request->username)->first();

            if($user)
            {
                $lapsed = 6;

                if($user->otp_sent_at)
                {
                    $lapsed = now()->diffInMinutes(Carbon::parse($user->otp_sent_at));
                }

                if($lapsed > 5)
                {
                    $scode             = 422;
                    $email_otp         = rand(1000, 9999);
                    $return['msg']     = 'Unable to process your request at this time. Please try again!';
                    $user->email_otp   = $email_otp;
                    $user->otp_sent_at = now();

                    // Send OTP to Email
                    if($user->save())
                    {
                        $scode         = 200;
                        $return['ok']  = 1;
                        $return['msg'] = 'OTP Sent Successfully to your registered email address!';
                    }
                }
                else
                {
                    $wait  = 5 - $lapsed;
                    $wait  = $wait ?: 1;

                    $scode = 200;
                    $return['msg'] = 'An otp is already sent to your registered email address. Please wait ' . (5 - $lapsed) . ' minutes before requesting another.';
                }
            }
        }

        return response($return, $scode);
    }


    /**
     *
     */
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'    => 'required|min:2',
            'email'   => 'required|email:filter|unique:users',
            'phone'   => 'required|numeric|min:6000000000|max:9999999999|unique:users',
            'password'=> ['required', 'confirmed', Password::min(6)],
        ], [
            'phone.min'   => 'Kindly enter a valid 10 digits phone no.',
            'phone.max'   => 'Kindly enter a valid 10 digits phone no.',
            'phone.unique'=> 'This phone no. has been already used.'
        ]);

        if(!$validation->fails())
        {
            $i        = 0;
            $nicename = Str::lower(str_replace(' ', '', $request->name));

            while(User::where('nicename', $nicename)->first())
            {
                $nicename = $nicename . '_' . $i;

                $i++;
            }

            // Create User
            $user = new User;

            $user->name        = $request->name;
            $user->email       = $request->email;
            $user->phone       = $request->phone;
            $user->role_id     = 4;
            $user->nicename    = $nicename;
            $user->password    = Hash::make($request->password);
            $user->email_token = Str::random(40);

            $user->save();

            // Send Verification Email
            $user->sendVerificationMail();

            return response()->json([
                'msg'=> 'Registration successfull!',
            ]);
        }

        return response()->json([
            'errors'=> $validation->errors()
        ], 402);
    }


    /**
     * Method to Logout User from requested Device
     *
     * @param NULL
     * @author Robin Tomar <robintomr@icloud.com>
     * @return array
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['msg'=> 'You\'ve been logged out successfully!'], 200);
    }
}
