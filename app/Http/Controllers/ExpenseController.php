<?php

namespace App\Http\Controllers;

use App\Models\AccHead;
use App\Models\Categories;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {

        try {
            if($request->has('expense_head_id')){
                $id = $request->expense_head_id;
                $expense_list = Expense::where('expense_head_id',$id)->paginate(20);
                $total = Expense::where('expense_head_id',$id)->sum('expense_amount');
            }
            else{
                $expense_list = Expense::orderByDesc('expense_id')->paginate(15);
                $total = Expense::sum('expense_amount');
            }
            $expense_head_list = AccHead::where('status','Active')->get();
            return view('main.expense.index', compact('expense_list','expense_head_list','total'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'expense_head_id' => 'required',
            'expense_amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ];

        $messages = [
            'expense_head_id.required' => 'Expense head required.',
            'expense_amount.required' => 'Amount required.',
            'expense_amount.min' => 'Amount should be greater than zero.',
            'expense_date.required' => 'Date required.',
            'expense_date.date' => 'Invalid date format.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('inval',$validator->messages())->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                Expense::insert([
                    'expense_head_id' => $request->expense_head_id,
                    'expense_amount' => $request->expense_amount,
                    'expense_date' => $request->expense_date,
                    'created_by' => Auth::user()->admin_id,
                ]);
            });
            return redirect()->back()->with('success', 'Expense information saved successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $id = $request->expense_id;

        if($id > 0 && is_numeric($id)) {
            $rules = [
                'expense_head_id' => 'required',
                'expense_amount' => 'required|numeric|min:0',
                'expense_date' => 'required|date',
            ];


            $messages = [
                'expense_head_id.required' => 'Expense head required.',
                'expense_amount.required' => 'Amount required.',
                'expense_amount.min' => 'Amount should be greater than zero.',
                'expense_date.required' => 'Date required.',
                'expense_date.date' => 'Invalid date format.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->with('inval', $validator->messages())->withInput();
            }

            try {
                DB::transaction(function () use ($request, $id) {
                    Expense::where('expense_id', $id)->update([
                        'expense_head_id' => $request->expense_head_id,
                        'expense_amount' => $request->expense_amount,
                        'expense_date' => $request->expense_date,
                        'updated_by' => Auth::user()->admin_id,
                    ]);
                });
                return redirect()->back()->with('success', 'Expense information updated successfully');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.' . $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error','Invalid parameter or request');
        }
    }
}
