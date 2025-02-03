<?php

namespace App\Http\Controllers;

use App\Models\CustomerWiseOrder;
use App\Models\DuePaid;
use App\Models\Expense;
use App\Models\ItemInfo;
use App\Models\OrderInfo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        try {
            $date = date("Y-m-d");
            $today_sale = OrderInfo::where('order_info_date', $date)->sum('order_info_total');
            $today_due = CustomerWiseOrder::where('cwo_date', $date)->sum('cwo_due');
            $today_expense = Expense::where('expense_date', $date)->sum('expense_amount');
            $total_sale = OrderInfo::sum('order_info_total');
            $total_due = CustomerWiseOrder::sum('cwo_due') - DuePaid::sum('dp_amount');
            $total_expense_amount = Expense::sum('expense_amount');
            $low_quantity_items = ItemInfo::where('item_quantity','<=',3)->take(2)->get();
            $order_list = OrderInfo::orderByDesc('order_info_id')->take(10)->get();
            return view('main.dashboard.index',compact('today_due','order_list','low_quantity_items','today_sale','total_expense_amount','total_sale','total_due','today_expense'));
        } catch (\Exception $e){
            return redirect('/');
        }
    }

    public function low_items(Request $request){
        try {
            $item_list = ItemInfo::where('item_quantity','<=',3)->where('item_status','Active')->paginate(15);
            return view('main.dashboard.low_items',compact('item_list'));
        } catch (\Exception $e){
            return redirect()->back();
        }
    }
}
