<?php

namespace App\Http\Controllers;

use App\Models\AccHead;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccHeadController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('sk')){
                $keyword = $request->sk;
                $expense_head_list = AccHead::where('name', 'like', '%' . $keyword . '%')->paginate(20);
            }
            else{
                $expense_head_list = AccHead::orderByDesc('id')->paginate(15);
            }
            return view('main.ac_head.index', compact('expense_head_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:100|unique:ac_head',
            'status' => 'required',
        ];

        $messages = [
            'name.required' => 'Name required.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'name.unique' => 'Name already exists.',
            'status.required' => 'Status required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                AccHead::insert([
                    'name' => $request->name,
                    'status' => $request->status,
                    'created_by' => Auth::user()->admin_id,
                ]);
            });
            return redirect()->back()->with('success', 'Account Head information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;

        if ($id > 0 && is_numeric($id)) {
            $rules = [
                'name' => ['required', 'max:100', Rule::unique('ac_head')->ignore($id, 'id')],
                'status' => 'required',
            ];


            $messages = [
                'name.required' => 'Name required.',
                'name.max' => 'Name cannot exceed 100 characters.',
                'name.unique' => 'Name already exists.',
                'status.required' => 'Status required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $id) {
                    AccHead::where('id', $id)->update([
                        'name' => $request->name,
                        'status' => $request->status,
                        'updated_by' => Auth::user()->admin_id,
                    ]);
                });
                return redirect()->back()->with('success', 'Account Head information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Invalid parameter or request');
        }
    }
}
