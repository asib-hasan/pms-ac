<?php

namespace App\Http\Controllers;

use App\Models\StoreInfo;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $auth_user = Auth::user();
        if($auth_user->admin_type == 1) {
            try {
                if ($request->has('sk')) {
                    $keyword = $request->sk;
                    $user_list = User::where('admin_name', 'like', '%' . $keyword . '%')->paginate(50);
                } else {
                    $user_list = User::paginate(15);
                }
                return view('main.user.index', compact('user_list'));
            } catch (QueryException $ex) {
                return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
    }

    public function store(Request $request)
    {
        $auth_user = Auth::user();
        if($auth_user->admin_type == 1) {
            $rules = [
                'admin_name' => 'required|string|max:100',
                'admin_email' => 'nullable|email',
                'admin_phone' => 'required|numeric|digits:11|unique:admin,admin_phone',
                'admin_password' => 'required|min:6',
                'admin_type' => 'required',
                'admin_status' => 'required',
            ];

            $messages = [
                'admin_name.required' => 'Name required.',
                'admin_name.max' => 'Store name cannot exceed 100 characters.',
                'admin_email.email' => 'Please enter a valid email address.',
                'admin_phone.required' => 'Phone required.',
                'admin_phone.numeric' => 'Phone number must be numeric.',
                'admin_phone.digits' => 'Phone number must be 11 digits long.',
                'admin_phone.unique' => 'Phone number already exists.',
                'admin_password.min' => 'Password must be at least 6 characters.',
                'admin_password.required' => 'Password required.',
                'admin_type.required' => 'Type required.',
                'admin_status.required' => 'Status required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request) {
                    User::insert([
                        'admin_name' => $request->admin_name,
                        'admin_email' => $request->admin_email,
                        'password' => Hash::make($request->admin_password),
                        'admin_phone' => $request->admin_phone,
                        'admin_status' => $request->admin_status,
                        'admin_type' => $request->admin_type
                    ]);
                });
                return redirect()->back()->with('success', 'User information saved successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
    }

    public function update(Request $request)
    {
        $auth_user = Auth::user();
        if($auth_user->admin_type == 1) {
            $id = $request->admin_id;
            if ($id > 0 && is_numeric($id)) {
                $rules = [
                    'admin_name' => 'required|string|max:100',
                    'admin_email' => 'nullable|email',
                    'admin_phone' => ['required','numeric','digits:11', Rule::unique('admin')->ignore($id, 'admin_id')],
                    'admin_type' => 'required',
                    'admin_status' => 'required',
                ];

                $messages = [
                    'admin_name.required' => 'Name required.',
                    'admin_name.max' => 'Store name cannot exceed 100 characters.',
                    'admin_email.email' => 'Please enter a valid email address.',
                    'admin_phone.required' => 'Phone number required.',
                    'admin_phone.numeric' => 'Phone number must be numeric.',
                    'admin_phone.unique' => 'Phone number already exists',
                    'admin_phone.digits' => 'Phone number must be 11 digits long.',
                    'admin_type.required' => 'Type required.',
                    'admin_status.required' => 'Status required.',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    return redirect()->back()->with('inval', $validator->messages())->withInput();
                }

                try {
                    DB::transaction(function () use ($request, $id) {
                        User::where('admin_id', $id)->update([
                            'admin_name' => $request->admin_name,
                            'admin_email' => $request->admin_email,
                            'password' => Hash::make($request->admin_password),
                            'admin_phone' => $request->admin_phone,
                            'admin_status' => $request->admin_status,
                            'admin_type' => $request->admin_type
                        ]);
                    });
                    return redirect()->back()->with('success', 'User information saved successfully');
                } catch (QueryException $e) {
                    return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'Invalid parameter or request');
            }
        }
        else{
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
    }
}
