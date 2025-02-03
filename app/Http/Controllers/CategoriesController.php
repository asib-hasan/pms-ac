<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('sk')){
                $keyword = $request->sk;
                $categories_list = Categories::where('item_category_name', 'like', '%' . $keyword . '%')->paginate(50);
            }
            else{
                $categories_list = Categories::orderByDesc('item_category_id')->paginate(15);
            }
            return view('main.categories.index', compact('categories_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'item_category_name' => 'required|max:100|unique:item_category,item_category_name',
        ];

        $messages = [
            'item_category_name.required' => 'Name required.',
            'item_category_name.max' => 'Name cannot exceed 100 characters.',
            'item_category_name.unique' => 'Name already exists.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                Categories::insert([
                    'item_category_name' => $request->item_category_name,
                    'item_category_status' => 'Active',
                ]);
            });
            return redirect()->back()->with('success', 'Category information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $id = $request->item_category_id;

        if($id > 0 && is_numeric($id)) {
            $rules = [
                'item_category_name' => ['required', 'max:100', Rule::unique('item_category')->ignore($id, 'item_category_id')],
                'item_category_status' => 'required',
            ];


            $messages = [
                'item_category_name.required' => 'Name required.',
                'item_category_name.unique' => 'Category name already exists.',
                'item_category_name.max' => 'Name cannot exceed 100 characters.',
                'item_category_status.required' => 'Status required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $id) {
                    Categories::where('item_category_id', $id)->update([
                        'item_category_name' => $request->item_category_name,
                        'item_category_status' => $request->item_category_status,
                    ]);
                });
                return redirect()->back()->with('success', 'Category information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }
}
