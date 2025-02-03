<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Companies;
use App\Models\CompanyContactInfo;
use App\Models\PurchaseBillPaid;
use App\Models\PurchaseInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompaniesController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('sk')){
                $keyword = $request->sk;
                $companies_list = Companies::where('item_company_name', 'like', '%' . $keyword . '%')->paginate(50);
            }
            else{
                $companies_list = Companies::orderByDesc('item_company_id')->paginate(15);
            }
            return view('main.companies.index', compact('companies_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'item_company_name' => 'required|max:100|unique:item_company,item_company_name',
        ];

        $messages = [
            'item_company_name.required' => 'Name required.',
            'item_company_name.max' => 'Name cannot exceed 100 characters.',
            'item_company_name.unique' => 'Name already exists.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                Companies::insert([
                    'item_company_name' => $request->item_company_name,
                    'item_company_status' => 'Active',
                    'created_by' => Auth::user()->admin_id,
                ]);
            });
            return redirect()->back()->with('success', 'Company information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $id = $request->item_company_id;

        if($id > 0 && is_numeric($id)) {
            $rules = [
                'item_company_name' => ['required', 'max:100', Rule::unique('item_company')->ignore($id, 'item_company_id')],
                'item_company_status' => 'required',
            ];


            $messages = [
                'item_company_name.required' => 'Name required.',
                'item_company_name.unique' => 'Name already exists.',
                'item_company_name.max' => 'Name cannot exceed 100 characters.',
                'item_company_status.required' => 'Status required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $id) {
                    Companies::where('item_company_id', $id)->update([
                        'item_company_name' => $request->item_company_name,
                        'item_company_status' => $request->item_company_status,
                        'updated_by' => Auth::user()->admin_id,
                    ]);
                });
                return redirect()->back()->with('success', 'Company information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function user(Request $request)
    {
        try {
            if (is_numeric($request->id) && $request->id > 0) {
                $company_id = $request->id;
                $company_info = Companies::where('item_company_id', $company_id)->first();
                if($company_info == ""){
                    return redirect()->back()->with('error', 'Company information not found.');
                }
                #getting contact list
                $contact_list = CompanyContactInfo::where('contact_info_company_id',$company_id)->paginate(15)->appends('id', $company_id);
                return view('main.companies.user', compact('company_info','contact_list'));
            }
            else{
                return redirect()->back()->with('error','Invalid parameter or request');
            }
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function user_store(Request $request)
    {
        $company_id = $request->contact_info_company_id;

        if($company_id > 0 && is_numeric($company_id)) {

            $rules = [
                'contact_info_company_id' => 'required',
                'contact_info_name' => 'required|max:100',
                'contact_info_email' => 'nullable|email',
                'contact_info_phone' => 'required|digits:11',
                'contact_info_fax' => 'nullable|max:20',
                'contact_info_designation' => 'nullable|max:100',
            ];

            $messages = [
                'contact_info_company_id.required' => 'Company ID is required.',
                'contact_info_name.required' => 'Name is required.',
                'contact_info_name.max' => 'Name cannot exceed 100 characters.',
                'contact_info_email.email' => 'Please provide a valid email address.',
                'contact_info_phone.required' => 'Phone number is required.',
                'phone.digits' => 'Phone number must be 11 digits long.',
                'contact_info_fax.max' => 'Fax cannot exceed 20 characters.',
                'contact_info_designation.string' => 'Designation must be a string.',
                'contact_info_designation.max' => 'Designation cannot exceed 100 characters.',
            ];


            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $company_id) {
                    CompanyContactInfo::insert([
                        'contact_info_company_id' => $company_id,
                        'contact_info_name' => $request->contact_info_name,
                        'contact_info_email' => $request->contact_info_email,
                        'contact_info_phone' => $request->contact_info_phone,
                        'contact_info_fax' => $request->contact_info_fax,
                        'contact_info_designation' => $request->contact_info_designation,
                        'created_by' => Auth::user()->admin_id,
                    ]);
                });
                return redirect()->back()->with('success', 'Contact information saved successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function user_update(Request $request)
    {
        $contact_info_id = $request->contact_info_id;

        if($contact_info_id > 0 && is_numeric($contact_info_id)) {

            $rules = [
                'contact_info_name' => 'required|max:100',
                'contact_info_email' => 'nullable|email',
                'contact_info_phone' => 'required|digits:11',
                'contact_info_fax' => 'nullable|max:20',
                'contact_info_designation' => 'nullable|max:100',
            ];

            $messages = [
                'contact_info_name.required' => 'Name is required.',
                'contact_info_name.max' => 'Name cannot exceed 100 characters.',
                'contact_info_email.email' => 'Please provide a valid email address.',
                'contact_info_phone.required' => 'Phone number is required.',
                'phone.digits' => 'Phone number must be 11 digits long.',
                'contact_info_fax.max' => 'Fax cannot exceed 20 characters.',
                'contact_info_designation.string' => 'Designation must be a string.',
                'contact_info_designation.max' => 'Designation cannot exceed 100 characters.',
            ];


            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $contact_info_id) {
                    CompanyContactInfo::where('contact_info_id',$contact_info_id)->update([
                        'contact_info_name' => $request->contact_info_name,
                        'contact_info_email' => $request->contact_info_email,
                        'contact_info_phone' => $request->contact_info_phone,
                        'contact_info_fax' => $request->contact_info_fax,
                        'contact_info_designation' => $request->contact_info_designation,
                    ]);
                });
                return redirect()->back()->with('success', 'Contact information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function user_delete(Request $request)
    {
        $contact_info_id = $request->contact_info_id;

        if($contact_info_id > 0 && is_numeric($contact_info_id)) {
            try {
                DB::transaction(function () use ($request, $contact_info_id) {
                    CompanyContactInfo::where('contact_info_id',$contact_info_id)->delete();
                });
                return redirect()->back()->with('success', 'Contact information deleted successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    #purchase
    public function purchase(Request $request)
    {
        try {
            if ($request->has('id') && $request->get('id') > 0) {
                $company_id = $request->id;
                $company_info = Companies::where('item_company_id', $company_id)->first();
                if($company_info == ""){
                    return redirect()->back()->with('error', 'Company information not found.');
                }
                #getting purchase list
                $purchase_list = PurchaseInfo::where('purchase_company_id',$company_id)->get();
                $total_purchase = PurchaseInfo::where('purchase_company_id',$company_id)->sum('purchase_total_amount');
                $total_paid_amount = (PurchaseBillPaid::where('bp_company_id',$company_id)->sum('bp_amount'));
                $total_due_amount = $total_purchase-$total_paid_amount;
                return view('main.companies.purchase', compact('company_info','purchase_list','total_purchase','total_due_amount','total_paid_amount'));
            }
            else{
                return redirect()->back()->with('error','Invalid parameter or request');
            }
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function purchase_store(Request $request)
    {
        $company_id = $request->purchase_company_id;

        if($company_id > 0 && is_numeric($company_id)) {

            $rules = [
                'purchase_date' => 'required|date',
                'purchase_invoice_no' => 'required|max:100',
                'purchase_total_amount' => 'required|numeric',
                'purchase_mode' => 'required|in:Paid,Due',
            ];

            $messages = [
                'purchase_date.required' => 'Purchase date required',
                'purchase_date.date' => 'Invalid date format',
                'purchase_invoice_no.required' => 'Invoice number required',
                'purchase_invoice_no.max' => 'Invoice number cannot exceed 100 characters',
                'purchase_total_amount.required' => 'Total purchase amount required',
                'purchase_total_amount.numeric' => 'Total purchase amount must be a numeric value',
                'purchase_mode.required' => 'Purchase mode required',
                'purchase_mode.in' => 'Purchase mode must be either paid or due',
            ];


            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $company_id) {
                    $id = PurchaseInfo::insertGetId([
                        'purchase_company_id' => $company_id,
                        'purchase_date' => $request->purchase_date,
                        'purchase_invoice_no' => $request->purchase_invoice_no,
                        'purchase_total_amount' => $request->purchase_total_amount,
                        'purchase_mode' => $request->purchase_mode,
                    ]);
                    if($request->purchase_mode == 'Paid') {
                        PurchaseBillPaid::insert([
                            'bp_purchase_id' => $id,
                            'bp_company_id' => $company_id,
                            'bp_amount' => $request->purchase_total_amount,
                            'bp_date' => $request->purchase_date,
                            'created_by' => Auth::user()->admin_id,
                        ]);
                    }
                });

                return redirect()->back()->with('success', 'Purchase information saved successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function purchase_due_update(Request $request)
    {
        $bp_purchase_id = $request->bp_purchase_id;
        $bp_company_id = $request->bp_company_id;
        $total_due_amount = $request->total_due_amount;

        if($bp_purchase_id > 0 && is_numeric($bp_purchase_id) && ($bp_company_id > 0) && is_numeric($bp_company_id) && $total_due_amount != "" && is_numeric($total_due_amount)) {

            $rules = [
                'bp_amount' => 'required|numeric',
                'bp_date' => 'required|date',
            ];

            $messages = [
                'bp_amount.required' => 'Purchase due amount required',
                'bp_date.required' => 'Purchase due amount required',
                'bp_date.date' => 'Invalid date format',
                'bp_amount.numeric' => 'Amount should be a numeric value',
            ];


            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            $amount = $request->bp_amount; $track = 0;

            if($amount > $total_due_amount){
                return redirect()->back()->with('error', 'Amount should be less than or equal to total due amount');
            }
            else if($amount == $total_due_amount){
                $track = 1;
            }

            try {
                DB::transaction(function () use ($request, $bp_purchase_id, $bp_company_id, $track) {
                    PurchaseBillPaid::insert([
                        'bp_purchase_id' => $bp_purchase_id,
                        'bp_company_id' => $bp_company_id,
                        'bp_amount' => $request->bp_amount,
                        'bp_date' => $request->bp_date,
                        'created_by' => Auth::user()->admin_id,
                    ]);
                    if($track == 1)
                    {
                        PurchaseInfo::where('purchase_id',$bp_purchase_id)->update([
                           'purchase_mode' => 'Paid',
                            'updated_by' => Auth::user()->admin_id,
                        ]);
                    }
                });
                return redirect()->back()->with('success', 'Due amount paid successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }
}
