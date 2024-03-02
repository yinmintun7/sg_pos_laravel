@extends('layouts.backend.master')
@section('title', 'Orderlist')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Order List </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <a href="{{ url('/sg-backend/shift/get-order-list-excel') }}/{{ $id }}" class="btn btn-primary" name="download">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    DownloadExcel
                                </a>
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th class="column-title">Order</th>
                                            <th class="column-title">Date</th>
                                            <th class="column-title">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order_list as $order)
                                            <tr class="even pointer">
                                                <td class=" ">{{ $order->order_no }}</td>
                                                <td class=" ">{{ $order->created_at }}</td>
                                                <td class=" ">{{ $order->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('layouts.backend.partial.footer_start')
    @include('layouts.backend.partial.footer_end')
    <!-- java script herr -->
    @include('layouts.backend.partial.footer_html_end')
@endsection
