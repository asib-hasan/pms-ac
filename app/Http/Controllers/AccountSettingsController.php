<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountSettingsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $auth_info = Auth::user();
            return view('main.account_settings.index',compact('auth_info'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong, please try again' . $e->getMessage());
        }
    }
    public function account_update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:11'
        ];

        $messages = [
            'name.required' => 'Name required',
            'name.max' => 'Name should not exceed 255 characters',
            'email.required' => 'Email required',
            'email.email' => 'Invalid email address',
            'phone.required' => 'Phone number required',
            'phone.numeric' => 'Invalid phone number format',
            'phone.digits' => 'Phone number should be 11 digits'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages());
        }

        DB::transaction(function () use ($request){
            User::where('admin_id',Auth::user()->admin_id)->update([
               'admin_name' => $request->name,
               'admin_email' => $request->email,
               'admin_phone' => $request->phone,
            ]);
        });

        return redirect()->back()->with('success','Account updated successfully');
    }

    #change password
    public function change_password(Request $request){
        $current_password = $request->current_password;
        $new_password = $request->new_password;

        if($current_password != "" && $new_password != ""){
            $auth_user = Auth::user();
            if(!Hash::check($current_password, $auth_user->password)){
                return redirect()->back()->with('error', 'Current password is incorrect');
            }
            if(strlen($new_password) < 6 || $new_password > 30){
                return redirect()->back()->with('error', 'Password should be within 6 to 30 characters');
            }

            DB::transaction(function () use ($auth_user, $new_password){
                User::where('admin_id',$auth_user->admin_id)->update([
                    'password' => Hash::make($new_password),
                ]);
            });

            return redirect()->back()->with('success','Password updated successfully');
        }
        else{
            return redirect()->back()->with('error', 'Invalid parameter or request');
        }
    }
}
