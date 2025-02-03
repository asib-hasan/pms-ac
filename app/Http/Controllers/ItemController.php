<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Companies;
use App\Models\ItemInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('sk')){
                $keyword = $request->sk;
                $item_list = ItemInfo::where('item_name', 'like', '%' . $keyword . '%')->paginate(50);
            }
            else{
                $item_list = ItemInfo::orderByDesc('item_id')->paginate(15);
            }
            return view('main.item.index', compact('item_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function add(Request $request)
    {
        try {
            $company_list = Companies::orderByDesc('item_company_id')->where('item_company_status','Active')->get();
            $category_list = Categories::orderByDesc('item_category_id')->where('item_category_status','Active')->get();
            return view('main.item.add', compact('company_list', 'category_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }


    public function store(Request $request)
    {
        $rules = [
            'item_name' => 'required|max:255|unique:item,item_name',
            'item_code' => 'nullable|max:255',
            'item_category_id' => 'required',
            'item_company_id' => 'required',
            'item_buy_price' => 'required|numeric',
            'item_sell_price' => 'required|numeric',
            'item_rack_no' => 'nullable|max:50',
            'item_quantity' => 'required|min:1',
            'item_reorder_level' => 'required|min:1',
            'item_expire_date' => 'nullable|date',
        ];

        $messages = [
            'item_name.required' => 'Item name required.',
            'item_name.max' => 'Item name cannot exceed 255 characters.',
            'item_name.unique' => 'Item name already exists.',
            'item_code.max' => 'Item code cannot exceed 255 characters.',
            'item_category_id.required' => 'Item category required.',
            'item_company_id.required' => 'Item company required.',
            'item_buy_price.required' => 'Buying price required.',
            'item_buy_price.numeric' => 'Buying price must be a numeric value.',
            'item_sell_price.required' => 'Selling price is required.',
            'item_sell_price.numeric' => 'Selling price must be a numeric value.',
            'item_rack_no.max' => 'Rack number cannot exceed 50 characters.',
            'item_quantity.required' => 'Quantity required.',
            'item_quantity.min' => 'Quantity must be at least 1.',
            'item_reorder_level.required' => 'Reorder level required.',
            'item_reorder_level.min' => 'Reorder level must be at least 1.',
            'item_expire_date.date' => 'Expire date must be a valid date.',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                ItemInfo::insert([
                    'item_name' => $request->item_name,
                    'item_code' => $request->item_code,
                    'item_category_id' => $request->item_category_id,
                    'item_company_id' => $request->item_company_id,
                    'item_buy_price' => $request->item_buy_price,
                    'item_sell_price' => $request->item_sell_price,
                    'item_rack_no' => $request->item_rack_no,
                    'item_quantity' => $request->item_quantity,
                    'item_reorder_level' => $request->item_reorder_level,
                    'item_expire_date' => $request->item_expire_date,
                    'item_status' => 'Active',
                ]);
            });
            return redirect('/item')->with('success', 'Item information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $item_id = $request->id;
        if($item_id > 0 && is_numeric($item_id)) {
            $item_info = ItemInfo::where('item_id', $item_id)->first();
            if ($item_info == "") {
                return redirect('/item')->with('error', 'Item information not found');
            }

            $company_list = Companies::orderByDesc('item_company_id')->where('item_company_status','Active')->get();
            $category_list = Categories::orderByDesc('item_category_id')->where('item_category_status','Active')->get();
            return view('main.item.edit', compact('company_list', 'category_list', 'item_info'));
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function update(Request $request)
    {
        $item_id = $request->item_id;
        if($item_id > 0 && is_numeric($item_id)) {
            $item_info = ItemInfo::where('item_id', $item_id)->first();
            if ($item_info == "") {
                return redirect('/item')->with('error', 'Item information not found');
            }

            #validation
            $rules = [
                'item_name' => ['required', 'max:255', Rule::unique('item')->ignore($item_id, 'item_id')],
                'item_code' => 'nullable|max:255',
                'item_category_id' => 'required',
                'item_company_id' => 'required',
                'item_buy_price' => 'required|numeric',
                'item_sell_price' => 'required|numeric',
                'item_rack_no' => 'nullable|max:50',
                'item_quantity' => 'required|min:1',
                'item_reorder_level' => 'required|min:1',
                'item_expire_date' => 'nullable|date',
            ];

            $messages = [
                'item_name.required' => 'Item name required.',
                'item_name.max' => 'Item name cannot exceed 255 characters.',
                'item_name.unique' => 'Item name already exists.',
                'item_code.max' => 'Item code cannot exceed 255 characters.',
                'item_category_id.required' => 'Item category required.',
                'item_company_id.required' => 'Item company required.',
                'item_buy_price.required' => 'Buying price required.',
                'item_buy_price.numeric' => 'Buying price must be a numeric value.',
                'item_sell_price.required' => 'Selling price is required.',
                'item_sell_price.numeric' => 'Selling price must be a numeric value.',
                'item_rack_no.max' => 'Rack number cannot exceed 50 characters.',
                'item_quantity.required' => 'Quantity required.',
                'item_quantity.min' => 'Quantity must be at least 1.',
                'item_reorder_level.required' => 'Reorder level required.',
                'item_reorder_level.min' => 'Reorder level must be at least 1.',
                'item_expire_date.date' => 'Expire date must be a valid date.',
            ];


            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request,$item_id) {
                    ItemInfo::where('item_id',$item_id)->update([
                        'item_name' => $request->item_name,
                        'item_code' => $request->item_code,
                        'item_category_id' => $request->item_category_id,
                        'item_company_id' => $request->item_company_id,
                        'item_buy_price' => $request->item_buy_price,
                        'item_sell_price' => $request->item_sell_price,
                        'item_rack_no' => $request->item_rack_no,
                        'item_quantity' => $request->item_quantity,
                        'item_reorder_level' => $request->item_reorder_level,
                        'item_expire_date' => $request->item_expire_date,
                        'item_status' => $request->item_status,
                    ]);
                });
                return redirect('/item')->with('success', 'Item information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }

    public function update_sell_price(Request $request)
    {
        $item_id = $request->item_id;
        $sell_price = $request->sell_price;

        if($item_id > 0 && is_numeric($item_id) && $sell_price != "" && is_numeric($sell_price)) {
            $item_info = ItemInfo::where('item_id',$item_id)->first();
            if($item_info == ""){
                return redirect('/item')->with('error','Item information not found');
            }

            try {
                DB::transaction(function () use ($request,$item_id,$sell_price) {
                    ItemInfo::where('item_id', $item_id)->update([
                        'item_sell_price' => $sell_price,
                    ]);
                });
                return redirect()->back()->with('success', 'Item sell price updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect('/item')->with('error', 'Invalid parameter or request');
        }
    }

    public function update_quantity(Request $request)
    {
        $item_id = $request->item_id;
        $more_quantity = $request->more_quantity;

        if($item_id > 0 && is_numeric($item_id) && $more_quantity > 0 && is_numeric($more_quantity)) {
            $item_info = ItemInfo::where('item_id',$item_id)->first();
            if($item_info == ""){
                return redirect('/item')->with('error','Item information not found');
            }
            $item_total_quantity = $item_info->item_quantity;
            $item_total_quantity += $more_quantity;

            try {
                DB::transaction(function () use ($request,$item_id,$item_total_quantity) {
                    ItemInfo::where('item_id', $item_id)->update([
                        'item_quantity' => $item_total_quantity,
                    ]);
                });
                return redirect()->back()->with('success', 'Item quantity updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect('/item')->with('error', 'Invalid parameter or request');
        }
    }
}
