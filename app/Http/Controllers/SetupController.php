<?php

namespace App\Http\Controllers;

use App\Models\StoreInfo;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    public function admin(){
        if(StoreInfo::exists()){
            return redirect('/');
        }
        return view('setup.step_one');
    }

    public function admin_store(Request $request){
         $rules = [
            'name' => 'required|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|numeric|digits:11|unique:admin,admin_phone',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required_with:password|same:password',
        ];
        $messages = [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 50 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters',
            'phone.required' => 'Phone number required.',
            'phone.unique' => 'Phone number already exists.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.digits' => 'Phone number must be 11 digits long.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'confirm_password.same' => 'Password does not match',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->with('invalid',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function() use ($request) {
                User::insert([
                    'admin_name' => $request->name,
                    'admin_email' => $request->email,
                    'password' => Hash::make($request->password),
                    'admin_phone' => $request->phone,
                    'admin_status' => 'Active',
                    'admin_type' => 1,
                ]);
            });
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }

        return redirect('/step/two');
    }

    public function step_two(){
        if(StoreInfo::exists()){
            return redirect('/');
        }
        return view('setup.step_two');
    }

    public function step_two_store(Request $request)
    {
        $rules = [
            'store_title' => 'required|string|max:100',
            'store_name' => 'required|string|max:255',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|numeric|digits:11',
            'store_address' => 'nullable|string|max:255',
        ];

        $messages = [
            'store_title.required' => 'Store title required.',
            'store_title.max' => 'Store title cannot exceed 100 characters.',
            'store_name.required' => 'Store name required.',
            'store_name.max' => 'Store name cannot exceed 255 characters.',
            'store_email.email' => 'Please enter a valid email address.',
            'store_email.max' => 'Email cannot exceed 255 characters.',
            'store_phone.numeric' => 'Phone number must be numeric.',
            'store_phone.digits' => 'Phone number must be 11 digits long.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('invalid',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                StoreInfo::insert([
                    'store_title' => $request->store_title,
                    'store_name' => $request->store_name,
                    'store_email' => $request->store_email,
                    'store_phone' => $request->store_phone,
                    'store_address' => $request->store_address,
                ]);
            });
            return redirect('/')->with('success', 'Store information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }
}
