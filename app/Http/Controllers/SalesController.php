<?php

namespace App\Http\Controllers;

use App\Models\CustomerInfo;
use App\Models\ItemInfo;
use App\Models\OrderDetails;
use App\Models\OrderInfo;
use App\Models\StoreInfo;
use App\Models\TempOrder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Str;
use PDF;

class SalesController extends Controller
{
    public function index(Request $request){
        try {
            $item_list = ItemInfo::orderByDesc('item_id')->where('item_status','Active')->get();
            $customer_list = CustomerInfo::orderByDesc('customer_id')->where('customer_status','Active')->get();
            //$session_id = session()->getId();
            #deleting temporary items
            DB::table('temp_order')->delete();
            return view('main.sales_corner.index', compact('item_list', 'customer_list'));
        }
        catch (QueryException $ex){
            return redirect()->back()->with('error', 'Process failed for - ' . $ex->getMessage());
        }
    }

    public function temp_order(Request $request)
    {
        $session_id = Session::getId();
        $temp_sales_cart = [];
        $count_error = 0;
        $return_array = [];

        $item_id = $request->item_name_type;

        $item = ItemInfo::where('item_id', $item_id)->first();

        if ($item) {
            if ($item->item_quantity > 0) {
                $tempOrder = TempOrder::where('temp_order_session_id', $session_id)
                    ->where('temp_order_item_id', $item_id)
                    ->first();

                if ($tempOrder) {
                    // Update the row
                    $newQty = $tempOrder->temp_order_qty + 1;
                    $profit = ($tempOrder->temp_order_item_sell_price - $item->item_buy_price) * $newQty;
                    $available_quantity = $item->item_quantity - $tempOrder->temp_order_qty;
                    if($available_quantity <= 0) {
                        $return_array = [
                            "output" => "error",
                            "msg" => "Insufficient quantity"
                        ];
                        return response()->json($return_array);
                    }
                    $tempOrder->update([
                        'temp_order_qty' => $newQty,
                        'temp_order_total' => $tempOrder->temp_order_item_sell_price * $newQty,
                        'temp_order_profit' => $profit,
                    ]);
                } else {
                    // Insert the order
                    $tempOrder = new TempOrder();
                    $tempOrder->temp_order_item_id = $item->item_id;
                    $tempOrder->temp_order_item_name = $item->item_name;
                    $tempOrder->temp_order_qty = 1;
                    $tempOrder->temp_order_item_buy_price = $item->item_buy_price;
                    $tempOrder->temp_order_item_sell_price = $item->item_sell_price;
                    $tempOrder->temp_order_item_expire_date = $item->item_expire_date;
                    $tempOrder->temp_order_total = $item->item_sell_price;
                    $tempOrder->temp_order_session_id = $session_id;
                    $tempOrder->temp_order_profit = $item->item_sell_price - $item->item_buy_price;

                    if (!$tempOrder->save()) {
                        $return_array = [
                            "output" => "error",
                            "msg" => "Order failed to add"
                        ];
                        return response()->json($return_array);
                    }
                }
            } else {
                # insufficient item
                $return_array = [
                    "output" => "error",
                    "msg" => "Insufficient quantity"
                ];
                return response()->json($return_array);
            }
        } else {
            # no data found
            $return_array = [
                "output" => "error",
                "msg" => "No data found in record"
            ];
            return response()->json($return_array);
        }

        # generating temp cart
        $temp_sales_cart = TempOrder::with('item_info')
            ->where('temp_order_session_id', $session_id)
            ->get();

        if ($temp_sales_cart->isEmpty()) {
            $return_array = [
                "output" => "error",
                "msg" => "Temp order data get failed"
            ];
            return response()->json($return_array);
        }

        # getting temp cart sub-total amount
        $temp_sales_sub_total = TempOrder::where('temp_order_session_id', $session_id)
            ->sum('temp_order_total');

        $return_array = [
            "output" => "success",
            "msg" => "Order placed successfully",
            "temp_sales_cart" => $temp_sales_cart,
            "temp_sales_sub_total" => $temp_sales_sub_total
        ];

        return response()->json($return_array);
    }

    public function change_quantity(Request $request){
        $session_id = session()->getId();
        $returnArray = [];
        $countError = 0;
        $orderId = $request->input('order_id');
        $itemId = $request->input('item_id');
        $sellPrice = $request->input('sell_price');
        $buyPrice = $request->input('buy_price');
        $itemQty = $request->input('item_qty');

        if (!empty($itemQty) && $itemQty > 0) {
            # check if item exists and fetch its quantity
            $item = ItemInfo::where('item_id', $itemId)->first();

            if ($item) {
                $availableQuantity = $item->item_quantity;

                if ($availableQuantity >= $itemQty) {
                    # calculate profit and update temp_order table
                    $profit = ($sellPrice - $buyPrice);
                    $calculatedProfit = $profit * $itemQty;

                    $updateData = [
                        'temp_order_qty' => $itemQty,
                        'temp_order_total' => ($sellPrice * $itemQty),
                        'temp_order_profit' => $calculatedProfit,
                    ];

                    $updated = TempOrder::where('temp_order_session_id', $session_id)
                        ->where('temp_order_id', $orderId)
                        ->update($updateData);

                    if (!$updated) {
                        $countError++;
                        $returnArray = [
                            "output" => "error",
                            "msg" => "Quantity not updated. Please try again."
                        ];
                    }
                } else {
                    # Retrieve previous quantity for error response
                    $tempOrder = TempOrder::where('temp_order_id', $orderId)
                        ->where('temp_order_session_id', $session_id)
                        ->first();

                    $previousQuantity = $tempOrder ? $tempOrder->temp_order_qty : '';

                    $countError++;
                    $returnArray = [
                        "output" => "error",
                        "msg" => "Quantity not available in record",
                        "previous_qty" => $previousQuantity
                    ];
                }
            } else {
                $countError++;
                $returnArray = [
                    "output" => "error",
                    "msg" => "No data found in record. Please check item storage"
                ];
            }
        } else {
            $countError++;
            $returnArray = [
                "output" => "error",
                "msg" => "Quantity required."
            ];
        }

        $tempSalesSubTotal = TempOrder::where('temp_order_session_id', $session_id)->sum('temp_order_total');

        # If no error, prepare success response
        if ($countError == 0) {
            $returnArray = [
                "output" => "success",
                "msg" => "Quantity updated successfully",
                "temp_sales_quantity" => $itemQty,
                "temp_sales_sub_total" => $tempSalesSubTotal,
                "temp_sales_sell_price" => $sellPrice
            ];
        }

        return response()->json($returnArray);
    }

    public function delete_item(Request $request)
    {
        $session_id = session()->getId();
        $returnArray = [];
        $countError = 0;
        $itemId = $request->input('item_id');

        if (!empty($itemId) && $itemId > 0) {
            // Delete item from temp_order
            $deleted = TempOrder::where('temp_order_id', $itemId)
                ->where('temp_order_session_id', $session_id)
                ->delete();

            if (!$deleted) {
                $countError++;
                $returnArray = [
                    "output" => "error",
                    "msg" => "Item not deleted. Please try again."
                ];
            } else {
                // Fetch updated data count
                $itemCount =  TempOrder::where('temp_order_session_id', $session_id)->count();
            }

            // Calculate updated subtotal
            $tempSalesSubTotal = TempOrder::where('temp_order_session_id', $session_id)
                ->sum('temp_order_total');

            // If no error, return success response
            if ($countError == 0) {
                $returnArray = [
                    "output" => "success",
                    "msg" => "Deleted successfully",
                    "temp_sales_sub_total" => $tempSalesSubTotal,
                    "item_count" => $itemCount ?? 0
                ];
            }
        }

        return response()->json($returnArray);
    }

    public function complete_order(Request $request)
    {
        $validated = $request->validate([
            'order_sub_total' => 'required|numeric',
            'order_discount' => 'nullable|numeric',
            'order_discount_type' => 'nullable|string',
            'order_total' => 'required|numeric',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'old_customer' => 'nullable|integer',
            'check_due' => 'required|numeric',
        ]);

        $session_id = session()->getId();
        $order_info_status = $validated['check_due'] > 0 ? 'Unpaid' : 'Paid';

        DB::beginTransaction();

        try {
            # fetch temp order data
            $tempOrders = DB::table('temp_order')
                ->where('temp_order_session_id', $session_id)
                ->get();

            if ($tempOrders->isEmpty()) {
                return response()->json(['output' => 'error', 'msg' => 'No items in the order'], 400);
            }

            # generate order tracking number
            $order_info_track_no = time() . rand(100, 999);

            # insert into `order_info`
            $orderInfo = [
                'order_info_session_id' => $session_id,
                'order_info_track_no' => $order_info_track_no,
                'order_info_subtotal' => $validated['order_sub_total'],
                'order_info_discount_type' => $validated['order_discount_type'],
                'order_info_discount' => $validated['order_discount'],
                'order_info_total' => $validated['order_total'],
                'order_info_due' => $validated['check_due'],
                'order_info_date' =>  date('Y-m-d'),
                'order_info_status' => $order_info_status,
                'created_by' => 12,
            ];
            $orderId = DB::table('order_info')->insertGetId($orderInfo);

            # insert into `order_details`
            foreach ($tempOrders as $order) {
                OrderDetails::insert([
                    'order_details_order_info_id' => $order_info_track_no,
                    'order_info_id' => $orderId,
                    'order_details_item_id' => $order->temp_order_item_id,
                    'order_details_item_name' => $order->temp_order_item_name,
                    'order_details_item_qty' => $order->temp_order_qty,
                    'order_details_item_sell_price' => $order->temp_order_item_sell_price,
                    'order_details_item_buy_price' => $order->temp_order_item_buy_price,
                    'order_details_item_expire_date' => $order->temp_order_item_expire_date,
                    'order_details_item_profit' => $order->temp_order_profit,
                    'order_details_date' => date('Y-m-d'),
                ]);

                ItemInfo::where('item_id', $order->temp_order_item_id)
                        ->decrement('item_quantity', $order->temp_order_qty);
            }

            # customer processing
            if (!empty($validated['old_customer'])) {
                DB::table('customer_wise_order')->insert([
                    'cwo_customer_id' => $validated['old_customer'],
                    'order_info_id' => $orderId,
                    'cwo_order_id' => $order_info_track_no,
                    'cwo_order_total' => $validated['order_total'],
                    'cwo_due' => $validated['check_due'],
                    'cwo_date' => date('Y-m-d'),
                ]);
            } else {
                if (!empty($validated['customer_name']) && !empty($validated['customer_phone'])) {
                    $existing_customer = CustomerInfo::where('customer_phone', $validated['customer_phone'])->first();
                    if ($existing_customer) {
                        DB::table('customer_wise_order')->insert([
                            'cwo_customer_id' => $existing_customer->id,
                            'cwo_order_id' => $order_info_track_no,
                            'order_info_id' => $orderId,
                            'cwo_order_total' => $validated['order_total'],
                            'cwo_due' => $validated['check_due'],
                            'cwo_date' => date('Y-m-d'),
                        ]);
                    } else {
                        $new_customer_id = CustomerInfo::insertGetId([
                            'customer_name' => $validated['customer_name'],
                            'customer_phone' => $validated['customer_phone'],
                            'customer_status' => 'Active',
                        ]);

                        DB::table('customer_wise_order')->insert([
                            'cwo_customer_id' => $new_customer_id,
                            'cwo_order_id' => $order_info_track_no,
                            'order_info_id' => $orderId,
                            'cwo_order_total' => $validated['order_total'],
                            'cwo_due' => $validated['check_due'],
                            'cwo_date' => date('Y-m-d'),
                        ]);
                    }
                }
            }

            # clear temporary data
            DB::table('temp_order')->where('temp_order_session_id', $session_id)->delete();

            DB::commit();

            return response()->json([
                'output' => 'success',
                'msg' => 'Sales completed successfully',
                'order_track_id' => $order_info_track_no,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['output' => 'error', 'msg' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function print_invoice(Request $request)
    {
        $order_track_no = $request->ot;
        if($order_track_no){
            $order_info = OrderInfo::where('order_info_track_no', $order_track_no)->first();
            $store_info = StoreInfo::first();
            $order_list = OrderDetails::where('order_info_id',$order_info->order_info_id)->get();

            $data = [
                'order_info' => $order_info,
                'store_info' => $store_info,
                'order_list' => $order_list,
            ];

            $pdf = PDF::loadView('main.sales_corner.invoice_pdf', $data);
            return $pdf->stream('report.pdf');
        }
    }
}
