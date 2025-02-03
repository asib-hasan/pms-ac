<?php

namespace App\Http\Controllers;

use App\Models\StoreInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    public function index(){
        $auth_user = Auth::user();
        if($auth_user->admin_type == 1) {
            try {
                $store_info = StoreInfo::first();
                return view('main.system_settings.index', compact('store_info'));
            } catch (QueryException $ex) {
                return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
    }

    public function update(Request $request){
        $auth_user = Auth::user();
        if($auth_user->admin_type == 1) {
            $rules = [
                'store_title' => 'required|max:100',
                'store_name' => 'required|max:255',
                'store_email' => 'nullable|email|max:255',
                'store_phone' => 'nullable|numeric|digits:11',
                'store_address' => 'nullable|max:255',
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
                return redirect()->back()->with('error', $validator->messages());
            }

            try {
                DB::transaction(function () use ($request) {
                    StoreInfo::where('id', 1)->update([
                        'store_title' => $request->store_title,
                        'store_name' => $request->store_name,
                        'store_email' => $request->store_email,
                        'store_phone' => $request->store_phone,
                        'store_address' => $request->store_address,
                    ]);
                });
                return redirect()->back()->with('success', 'System settings information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
    }
}
