<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Expense;
use App\Models\OrderDetails;
use App\Models\OrderInfo;
use App\Models\StoreInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use PDF;
class ReportController extends Controller
{
    public function index(Request $request){
        return view('main.report.index');
    }

    public function sales_report(Request $request){
        try {
            $start_date = $end_date = $flag = "";
            $gross_amount = $gross_profit = 0;
            $order_list = array();
            if($request->has(['start_date','end_date'])){
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $flag = 1;
                $order_list = OrderDetails::whereBetween('order_details_date', [$start_date, $end_date])->get();
                $gross_amount = OrderInfo::whereBetween('order_info_date', [$start_date, $end_date])->sum('order_info_total');
                $discount_amount = OrderInfo::whereBetween('order_info_date', [$start_date, $end_date])->sum('order_info_discount');
                $gross_profit = OrderDetails::whereBetween('order_details_date', [$start_date, $end_date])->sum('order_details_item_profit') - $discount_amount;
            }
            return view('main.report.sales_report', compact('start_date','end_date','gross_amount','gross_profit','flag','order_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function print_sales_report(Request $request){
        try {
            if($request->has(['start','end'])){
                $start_date = $request->start;
                $end_date = $request->end;
                $order_list = OrderDetails::whereBetween('order_details_date', [$start_date, $end_date])->get();
                $gross_amount = OrderDetails::whereBetween('order_details_date', [$start_date, $end_date])->sum('order_details_item_sell_price');
                $gross_profit = OrderDetails::whereBetween('order_details_date', [$start_date, $end_date])->sum('order_details_item_profit');
                $store_info = StoreInfo::first();

                $data = [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'order_list' => $order_list,
                    'gross_amount' => $gross_amount,
                    'gross_profit' => $gross_profit,
                    'store_info' => $store_info
                ];

                $pdf = PDF::loadView('main.report.sales_report_pdf', $data);
                return $pdf->stream('report.pdf');
            }
            else{
                return redirect()->back()->with('error', 'Invalid parameter or request');
            }
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    #expense
    public function expense_report(Request $request){
        try {
            $start_date = $end_date = $flag = "";
            $gross_amount = 0;
            $expense_list = array();
            if($request->has(['start_date','end_date'])){
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $flag = 1;
                $expense_list = Expense::whereBetween('expense_date', [$start_date, $end_date])->get();
                $gross_amount = Expense::whereBetween('expense_date', [$start_date, $end_date])->sum('expense_amount');
            }
            return view('main.report.expense_report', compact('start_date','end_date','gross_amount','flag','expense_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function expense_report_print(Request $request){
        try {
            if($request->has(['start','end'])){
                $start_date = $request->start;
                $end_date = $request->end;
                $expense_list = Expense::whereBetween('expense_date', [$start_date, $end_date])->get();
                $gross_amount = Expense::whereBetween('expense_date', [$start_date, $end_date])->sum('expense_amount');
                $store_info = StoreInfo::first();

                $data = [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'expense_list' => $expense_list,
                    'gross_amount' => $gross_amount,
                    'store_info' => $store_info
                ];

                $pdf = PDF::loadView('main.report.expense_report_pdf', $data);
                return $pdf->stream('report.pdf');
            }
            else{
                return redirect()->back()->with('error', 'Invalid parameter or request');
            }
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }
}
