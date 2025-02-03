<?php

namespace App\Http\Controllers;

use App\Models\CustomerInfo;
use App\Models\CustomerWiseOrder;
use App\Models\DuePaid;
use App\Models\OrderInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('sk')){
                $keyword = $request->sk;
                $customer_list = CustomerInfo::where('customer_name', 'like', '%' . $keyword . '%')->paginate(50);
            }
            else{
                $customer_list = CustomerInfo::orderByDesc('customer_id')->paginate(15);
            }
            return view('main.customer.index', compact('customer_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }


    public function store(Request $request)
    {
        $rules = [
            'customer_name' => 'required|max:100',
            'customer_phone' => 'required|digits:11',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|max:255',
        ];

        $messages = [
            'customer_name.required' => 'Customer name required',
            'customer_phone.required' => 'Customer phone required',
            'customer_phone.digits' => 'Customer phone must be 11 digits',
            'customer_email.email' => 'Customer email is not valid',
            'customer_address.max' => 'Customer address should not exceed 255 characters',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        if(CustomerInfo::where('customer_name',$request->customer_name)->where('customer_phone',$request->customer_phone)->exists()){
            return redirect()->back()->with('error','Customer already exist')->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                CustomerInfo::insert([
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $request->customer_address,
                    'customer_email' => $request->customer_email,
                    'customer_status' => 'Active',
                ]);
            });
            return redirect('/customer')->with('success', 'Customer information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $customer_id = $request->customer_id;
        if($customer_id > 0 && is_numeric($customer_id)) {
            $customer_info = CustomerInfo::where('customer_id', $customer_id)->first();
            if ($customer_info == "") {
                return redirect('/item')->with('error', 'Customer information not found');
            }

            #validation
            $rules = [
                'customer_name' => 'required|max:255',
                'customer_phone' => 'required|digits:11',
                'customer_email' => 'nullable|email',
                'customer_address' => 'nullable|max:255',
            ];

            $messages = [
                'customer_name.required' => 'Customer name required',
                'customer_phone.required' => 'Customer phone required',
                'customer_phone.digits' => 'Customer phone must be 11 digits',
                'customer_email.email' => 'Customer email is not valid',
                'customer_address.max' => 'Customer address should not exceed 255 characters',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            if(CustomerInfo::where('customer_name',$request->customer_name)->where('customer_id' , '!=', $customer_id)->where('customer_phone',$request->customer_phone)->exists()){
                return redirect()->back()->with('error','Customer already exist')->withInput();
            }

            try {
                DB::transaction(function () use ($request,$customer_id) {
                    CustomerInfo::where('customer_id', $customer_id)->update([
                        'customer_name' => $request->customer_name,
                        'customer_phone' => $request->customer_phone,
                        'customer_address' => $request->customer_address,
                        'customer_email' => $request->customer_email,
                        'customer_status' => $request->customer_status
                    ]);
                });
                return redirect('/customer')->with('success', 'Customer information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function customer_order(Request $request){
        $id = $request->id;
        if(is_numeric($id) && $id > 0){
            $customer_info = CustomerInfo::where('customer_id', $id)->first();
            if($customer_info == ""){
                return redirect('/customer')->with('error', 'Customer information not found');
            }
            $customer_order_list = CustomerWiseOrder::where('cwo_customer_id',$id)->orderByDesc('cwo_id')->get();
            return view('main.customer.order', compact('customer_order_list','customer_info'));
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }
    public function customer_due_paid(Request $request){
        $id = $request->id;
        if(is_numeric($id) && $id > 0){
            $customer_info = CustomerInfo::where('customer_id', $id)->first();
            if($customer_info == ""){
                return redirect('/customer')->with('error', 'Customer information not found');
            }
            $customer_paid_list = DuePaid::where('dp_customer_id',$id)->orderByDesc('dp_id')->paginate(15)->appends('id',$id);
            return view('main.customer.payment_history', compact('customer_paid_list','customer_info'));
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function payment_update(Request $request)
    {
        $customer_id = $request->customer_id;
        $due_amount = $request->due_amount;
        $pay_amount = $request->pay_amount;
        if(is_numeric($customer_id) && $customer_id > 0 && is_numeric($pay_amount) && $pay_amount > 0 && is_numeric($due_amount) && $due_amount > 0) {
            $customer_info = CustomerInfo::where('customer_id', $customer_id)->first();
            if ($customer_info == "") {
                return redirect('/item')->with('error', 'Customer information not found');
            }
            if($pay_amount > $due_amount){
                return redirect('/customer')->with('error','Pay amount should not greater than due amount');
            }
            try {
                DB::transaction(function () use ($request,$customer_id) {
                    DuePaid::insert([
                        'dp_customer_id' => $customer_id,
                        'dp_amount' => $request->pay_amount,
                        'dp_date' => $request->date,
                        'created_by' => Auth::user()->admin_id,
                    ]);

                });
                return redirect('/customer')->with('success', 'Customer due paid successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }
}
