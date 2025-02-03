<?php

namespace App\Http\Controllers;

use App\Models\CustomerInfo;
use App\Models\StoreInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(Request $request){
        if(Auth::check()){
            return redirect('dashboard');
        }
        else if(!StoreInfo::exists()){
            return redirect('step/one');
        }
        else {
            $store_info = StoreInfo::first();
            return view('login.index', compact('store_info'));
        }
    }

    public function authenticate(Request $request){
        $rules = [
            'phone' => 'required|numeric|digits:11',
            'password' => 'required',
        ];

        $messages = [
            'phone.required' => 'Phone required',
            'password.required' => 'Password required',
            'phone.numeric' => 'Invalid phone number',
            'phone.digits' => 'Phone number should be 11 digits',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages());
        }

        if (Auth::attempt(['admin_phone' => $request->phone, 'password' => $request->password])) {
            return redirect()->intended('/dashboard');
        }
        else{
            return redirect('/')->with('error','Invalid phone number or password')->withInput();
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
