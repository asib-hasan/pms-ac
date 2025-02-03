@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">{{ $item_list->count() }} Low Quantity Items Found</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #ddd">
                                                <th>Item Info</th>
                                                <th>Price Info</th>
                                                <th>Quantity</th>
                                                <th>Rack No</th>
                                                <th>Company Name</th>
                                                <th>Expire Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($item_list AS $item)
                                                <tr>
                                                    <td>
                                                        <b>{{ $item->item_name }}</b><br>({{ $item->item_category_info->item_category_name }})
                                                        @if (!empty($item->item_code))
                                                            <br><small style="color: green">(# {{ $item->item_code }})</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        Buy: {{ $currency }}&nbsp;{{ $item->item_buy_price }}
                                                        <br>Sell: {{ $currency }}&nbsp;{{ $item->item_sell_price }}
                                                        <a href="javascript:void(0);"><span data-toggle="modal" data-target="#sell_{{ $item->item_id }}"><i class="fa fa-pencil"></i></span></a>
                                                        <div class="modal fade" id="sell_{{ $item->item_id }}" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content" style="border: 0px;">
                                                                    <form method="POST" action="{{ url('/item/update/sell/price') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="item_id" value="{{ $item->item_id }}"/>
                                                                        <div class="card">
                                                                            <div class="card-header font-weight-bold">Update Sell Price For<b style="color: maroon"> {{ $item->item_name }}</b>
                                                                                <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label>Sell Price<b class="required_mark">*</b></label>
                                                                                            <input type="text" maxlength="5" class="form-control" name="sell_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="" required/>
                                                                                            <small style="color: red">Please input only numeric values</small>
                                                                                        </div>
                                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $item->item_quantity }}&nbsp;
                                                        <a href="javascript:void(0);"><span data-toggle="modal" data-target="#load_{{ $item->item_id }}"><i class="fa fa-plus-circle"></i></span></a>
                                                        <div class="modal fade" id="load_{{ $item->item_id }}" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content" style="border: 0px">
                                                                    <form method="POST" action="{{ url('/item/update/quantity') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="item_id" value="{{ $item->item_id }}"/>
                                                                        <div class="card">
                                                                            <div class="card-header font-weight-bold">Load More Quantity For <b style="color: maroon">{{ $item->item_name }}</b>
                                                                                <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label>Quantity<b class="required_mark">*</b></label>
                                                                                            <input type="number" class="form-control" min="1" name="more_quantity" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required/>
                                                                                        </div>
                                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if ($item->item_quantity <= $item->item_reorder_level)
                                                            <br><small style="color: maroon;font-weight: bold">reorder</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->item_rack_no }}</td>
                                                    <td>{{ $item->item_company_info->item_company_name }}</td>
                                                    <td>
                                                        @if (!empty($item->item_expire_date))
                                                            @if ($item->item_expire_date < date('Y-m-d'))
                                                                <b style="color: #ed5565;">
                                                                    @php
                                                                        $date = date_create($item->item_expire_date);
                                                                        echo date_format($date, "d-M-Y");
                                                                    @endphp
                                                                </b>
                                                            @else
                                                                @php
                                                                    $date = date_create($item->item_expire_date);
                                                                    echo date_format($date, "d-M-Y");
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div>
                                            {{ $item_list->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#dashboard').addClass('active');
    </script>

@endsection
