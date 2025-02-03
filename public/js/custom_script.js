
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function check_click_item(e, product) {

    var item_name_id = product;

    if (item_name_id === '') {
        alert("Enter item name");
    } else {
        $("#item_name_type").val("");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/view/pos",
            dataType: "json",
            data: {
                item_name_type: item_name_id
            },
            success: function (response) {
                if (response.output === "success") {
                    var temp_sales_cart = response.temp_sales_cart;
                    var temp_sales_sub_total = response.temp_sales_sub_total;
                    console.log(temp_sales_cart);
                    console.log(temp_sales_sub_total);
                    $("#sub_total_price").val(temp_sales_sub_total);
                    generate_table(temp_sales_cart, temp_sales_sub_total);
                } else {
                    alert(response.msg);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText); // Improved error logging
                alert("An error occurred. Please try again...");
            }
        });
    }
}


function generate_table(temp_sales_cart_obj, temp_sales_sub_total_obj) {
    var countItem = temp_sales_cart_obj.length;
    var countRow = 1;

    var tableHTML = '';
    if (countItem > 0) {
        tableHTML += '<table class="table table-bordered table-sm">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += '<th style="background-color: #ddd">#</th>';
        tableHTML += '<th style="background-color: #ddd">Item Name</th>';
        tableHTML += '<th style="background-color: #ddd">Expire Date</th>';
        tableHTML += '<th style="background-color: #ddd">In Stock</th>';
        tableHTML += '<th style="background-color: #ddd">Sell Qty</th>';
        tableHTML += '<th style="background-color: #ddd">Sell Price (৳)</th>';
        tableHTML += '<th style="background-color: #ddd">Total Price (৳)</th>';
        tableHTML += '<th style="background-color: #ddd">Action</th>';
        tableHTML += '</tr>';
        tableHTML += '</thead>';

        tableHTML += '<tbody id="temp_table_body">';
        $.each(temp_sales_cart_obj, function (key, order) {
            tableHTML += '<tr id="itemRow_' + order.temp_order_id + '">';
            tableHTML += '<td>' + countRow + '</td>';
            tableHTML += '<td>' + order.temp_order_item_name + '</td>';
            tableHTML += '<td>' + order.temp_order_item_expire_date + '</td>';

            // Access item_quantity directly from the item_info relationship
            if (order.item_info) {
                tableHTML += '<td>' + order.item_info.item_quantity + '</td>';
            } else {
                tableHTML += '<td>N/A</td>'; // Handle case where item_info is null
            }

            tableHTML += '<td><input style="border:1px solid #ddd;border-radius: 5px;padding:5px" onchange="javascript:change_quantity(' + order.temp_order_id + ',' + order.temp_order_item_id + ',' + order.temp_order_item_sell_price + ',' + order.temp_order_item_buy_price + ')" id="temp_order_item_quantity_' + order.temp_order_id + '" type="number" min="1" value="' + order.temp_order_qty + '" /></td>';
            tableHTML += '<td>' + order.item_info?.item_sell_price + '</td>'; // Use optional chaining for safety
            tableHTML += '<td><span id="item_total_price_' + order.temp_order_id + '">' + order.temp_order_total + '</span></td>'; // Use optional chaining for safety
            tableHTML += '<td><a href="javascript:void(0);" style="color: #9b1b25; font-size:15px;" onclick="javascript:delete_item(' + order.temp_order_id + ');"><i class="fa fa-trash"></i></a></td>';
            tableHTML += '</tr>';
            countRow++;
        });

        tableHTML += '</tbody>';
        tableHTML += '<tfoot>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Subtotal (৳)</th><td colspan="2"><span id="total_sales_subtotal">' + temp_sales_sub_total_obj + '</span></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Discount Type</th><td colspan="2"><select style="border:1px solid #ddd;border-radius: 5px;padding:5px" id="discount_type" name="discount_type" onchange="javascript:check_discount_type(event, this.value);"><option value="0">-- Choose --</option><option value="1">Flat Rate</option><option value="2">Percent</option></select></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Discount&nbsp;(৳)</th><td colspan="2"><input style="border:1px solid #ddd;border-radius: 5px;padding:5px" id="discount_price" type="number" min="1" onkeyup="javascript:discount_calculate(this.value);" /></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Gross Total&nbsp;(৳)</th><td colspan="2"><span id="gross_total">' + temp_sales_sub_total_obj + '</span></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Total Paid&nbsp;(৳)</th><td colspan="2"><input style="border:1px solid #ddd;border-radius: 5px;padding:5px" id="total_paid" type="number" min="1" onkeyup="javascript:due_calculate(this.value);" /></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Due Amount&nbsp;(৳)</th><td colspan="2"><span id="due_total">' + temp_sales_sub_total_obj + '</span></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Return Amount&nbsp;(৳)</th><td colspan="2"><span id="return_total"></span></td></tr>';
        tableHTML += '</tfoot>';
        tableHTML += '</table>';

    } else {
        console.log('No item in cart');
    }

    $('#sales_table_data').html(tableHTML);
}



function delete_item(id) {
    var item_id = id;
    if (item_id != '') {
        // post data using ajax
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/delete/item",
            data: {
                item_id: item_id
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    var table_empty_html = '<tr><td colspan="8">No data found</td></tr>';
                    var temp_sales_sub_total = obj.temp_sales_sub_total;
                    var item_count = obj.item_count;
                    $("#itemRow_" + item_id).css('background-color', '#FFE0E0');
                    setTimeout(function () {
                        $("#itemRow_" + item_id).fadeOut("slow");
                    }, 50);
                    $("#sub_total_price").val(temp_sales_sub_total);
                    $("#total_sales_subtotal").text(temp_sales_sub_total);
                    $("#gross_total").text(temp_sales_sub_total);
                    $("#discount_price").val('');
                    $("#due_total").text('');
                    $("#total_paid").val('');
                    if (item_count === 0) {
                        $("#temp_table_body").html(table_empty_html);
                    }
                } else {
                    alert(obj.msg);
                }
            }
        });
    }
}

function change_quantity(order_id, item_id, sell_price, buy_price) {
    var order_id = order_id;
    var item_id = item_id;
    var sell_price = sell_price;
    var buy_price = buy_price;
    var item_qty = $("#temp_order_item_quantity_" + order_id).val();
    console.log(item_qty);
    console.log(order_id);
    console.log(item_id);
    console.log(sell_price);
    if (item_qty === '' || item_qty < 1) {
        alert("Please enter item quantity");
    } else {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/change/quantity",
            data: {
                order_id: order_id,
                item_id: item_id,
                sell_price: sell_price,
                buy_price: buy_price,
                item_qty: item_qty
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    var sell_price = obj.temp_sales_sell_price;
                    var item_total_price = (sell_price * item_qty);
                    item_total_price = parseFloat(item_total_price).toFixed(2);
                    var sub_total = obj.temp_sales_sub_total;
                    var sub_total_text = sub_total;
                    var item_total_price_text = item_total_price;
                    $("#item_total_price_" + order_id).text(item_total_price_text);
                    $("#total_sales_subtotal").text(sub_total_text);
                    $("#gross_total").text(sub_total_text);
                    $("#due_total").text(sub_total_text);
                    $("#sub_total_price").val(sub_total);
                } else {
                    console.log(obj.previous_qty);
                    $("#temp_order_item_quantity_" + order_id).val(obj.previous_qty);
                    alert(obj.msg);
                }
            }
        });
    }
}

function check_discount_type(e, discount_type) {
    var discount_val = $("#discount_price").val();
    var sub_total_price = $("#sub_total_price").val();
    $("#total_paid").val('');
    $("#due_total").text('');
    if (discount_type == "0") {
        $("#discount_price").val('');
        $("#gross_total").text(sub_total_price);
        $("#due_total").text(sub_total_price);
        alert("Choose discount type to calculate discount amount");
    } else {
        if (discount_val > 0 && discount_val != '') {

            var discount_price = discount_val;
            var calculate_grand_total = 0.00;
            var price_1 = parseFloat(sub_total_price).toFixed(2);//sub total
            var price_2 = parseFloat(discount_price).toFixed(2);// discount
            if (discount_type == 2) {
                // check discount not more than 100 percent
                if (price_2 > 100.00) {
                    alert('Discount cannot be more than 100 percent');
                    $("#discount_price").val('');
                    $("#gross_total").text(sub_total_price);
                    $("#due_total").text(sub_total_price);
                } else {
                    var calculate_discount = parseFloat(parseFloat(price_2 / 100).toFixed(2) * parseFloat(price_1).toFixed(2));
                    calculate_grand_total = parseFloat(price_1 - calculate_discount).toFixed(2);
                    if (parseInt(calculate_grand_total) > parseInt(price_1)) {
                        alert('Amount should not be negative value or greater than total price');
                        $("#discount_price").val('');
                        $("#gross_total").text(sub_total_price);
                        $("#due_total").text(sub_total_price);
                    } else {
                        $("#gross_total").text(calculate_grand_total);
                        $("#due_total").text(calculate_grand_total);
                    }
                }
            } else {
                // flat discount calculation
                if (parseInt(price_2) > parseInt(price_1)) {
                    alert('Amount should not be negative value or greater than total price');
                    $("#discount_price").val('');
                    $("#gross_total").text(sub_total_price);
                    $("#due_total").text(sub_total_price);
                } else {
                    calculate_grand_total = parseFloat(price_1 - price_2).toFixed(2);
                    $("#gross_total").text(calculate_grand_total);
                    $("#due_total").text(calculate_grand_total);
                }
            }
        } else {
            var sub_total_price = $("#sub_total_price").val();

            $("#gross_total").text(sub_total_price);
            $("#due_total").text(sub_total_price);
            alert('Enter discount amount');
            $("#discount_price").focus();
        }
    }
}
function discount_calculate(discount_val) {

    var discount_type = $("#discount_type :selected").val();
    var sub_total_price = $("#sub_total_price").val();
    $("#total_paid").val('');
    $("#due_total").text('');
    if (discount_type == "0") {
        $("#discount_price").val('');
        $("#gross_total").text(sub_total_price);
        $("#due_total").text(sub_total_price);
        alert("Choose discount type to calculate discount amount");
    } else {
        if (discount_val > 0 && discount_val != '') {

            var discount_price = discount_val;
            var calculate_grand_total = 0.00;
            var price_1 = parseFloat(sub_total_price).toFixed(2);//sub total
            var price_2 = parseFloat(discount_price).toFixed(2);// discount
            if (discount_type == 2) {
                // check discount not more than 100 percent
                if (price_2 > 100.00) {
                    alert('Discount cannot be more than 100 percent');
                    $("#discount_price").val('');
                    $("#gross_total").text(sub_total_price);
                    $("#due_total").text(sub_total_price);
                } else {
                    var calculate_discount = parseFloat(parseFloat(price_2 / 100).toFixed(2) * parseFloat(price_1).toFixed(2));
                    calculate_grand_total = parseFloat(price_1 - calculate_discount).toFixed(2);
                    if (parseInt(calculate_grand_total) > parseInt(price_1)) {
                        alert('Amount should not be negative value or greater than total price');
                        $("#discount_price").val('');
                        $("#gross_total").text(sub_total_price);
                        $("#due_total").text(sub_total_price);
                    } else {
                        $("#gross_total").text(calculate_grand_total);
                        $("#due_total").text(calculate_grand_total);
                    }
                }
            } else {
                // flat discount calculation
                if (parseInt(price_2) > parseInt(price_1)) {
                    alert('Amount should not be negative value or greater than total price');
                    $("#discount_price").val('');
                    $("#gross_total").text(sub_total_price);
                    $("#due_total").text(sub_total_price);
                } else {
                    calculate_grand_total = parseFloat(price_1 - price_2).toFixed(2);
                    $("#gross_total").text(calculate_grand_total);
                    $("#due_total").text(calculate_grand_total);
                }
            }
        } else {
            var sub_total_price = $("#sub_total_price").val();

            $("#gross_total").text(sub_total_price);
            $("#due_total").text(sub_total_price);
            alert('Enter discount amount');
            $("#discount_price").focus();
        }
    }
}

function due_calculate(total_paid) {
    var total_paid = total_paid;
    var gross_amount = parseFloat(Math.round($("#gross_total").text() * 100) / 100).toFixed(2); // gross amount
    var total_amount = parseFloat(Math.round(total_paid * 100) / 100).toFixed(2); // total paid
    if (total_amount > 0 && total_amount != '') {
        var due = parseFloat(gross_amount - total_amount).toFixed(2);


        if (due >= 0) {
            $("#due_total").text(due);
            $("#return_total").text("");
        } else {
            $("#due_total").text('0.0');
            var return_amount = parseFloat(total_amount - gross_amount).toFixed(2);
            $("#return_total").text(return_amount);
        }
    } else {
        $("#due_total").text(gross_amount);
        $("#return_total").text("");
    }

}

/*
 * Complete sales / cart data
 */
function complete_sale() {

    var confirm_sale = confirm("Are you sure want to confirm payment");
    if (confirm_sale === true) {
        var order_sub_total = $("#sub_total_price").val();
        var order_discount_type = $("#discount_type :selected").val();
        var order_discount = $("#discount_price").val();
        var order_total = $("#gross_total").text();
        var total_paid = $("#total_paid").val();
        var customer_name = $("#customer_name").val();
        var customer_phone = $("#customer_phone").val();
        var old_customer = $("#old_customer :selected").val();
        var due_total = $("#due_total").text();
        var check_due = parseFloat(due_total).toFixed(2);
        var status = 0;

        if (order_sub_total != '' && order_total != '') {
            if (check_due > 0) {
                if (old_customer == '') {
                    if (customer_name == '' || customer_phone == '') {
                        alert('Please search customer from existing list or add new customer');
                        status = 1;
                    } else {
                        status = 0;
                    }
                } else {
                    status = 0;
                }
            } else {
                status = 0;
            }

            if (status == 0) {
                $.ajax({
                    type: "POST",
                    url: "/pos/complete/order",
                    dataType: "json",
                    data: {
                        order_sub_total: order_sub_total,
                        order_discount: order_discount,
                        order_discount_type: order_discount_type,
                        order_total: order_total,
                        customer_name: customer_name,
                        customer_phone: customer_phone,
                        old_customer: old_customer,
                        check_due: check_due
                    },
                    success: function (response) {
                        var obj = response;
                        if (obj.output === "success") {
                            if ($('#is_invoice').is(":checked")) {
                                var order_track_id = obj.order_track_id;
                                var invoice_url = 'print/pos/invoice?ot=' + order_track_id;
                                window.open(invoice_url, '_blank');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 100);
                                console.log(obj.msg);
                                console.log(order_track_id);
                            } else {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 100);
                            }

                        } else {
                            alert(obj.msg);
                        }
                    }
                });
            }
        } else {
            alert('Your cart is empty. Please add item in cart.');
        }
    }
}


