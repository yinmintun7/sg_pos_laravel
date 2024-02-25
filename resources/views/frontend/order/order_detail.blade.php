@extends('layouts.frontend.master')
@section('title', 'Payment')
@section('content')
    <div class="col-lg-8 col-md-7 col-sm-6 col-6 receipt-btn justify-content-start">
        <button class="btn" onclick="history.back()">

            <img src="{{asset('asset/images/frontend/payment/previous_img.png')}}" alt="Previous"
                class="heightLine_06" />
            </a>
        </button>
    </div>
    <div class="containder_fluid" ng-app="myApp" ng-controller="myCtrl" ng-init="orderDetail(<?php echo $id; ?>)">
        <div id="order-detail">
            <div class="container"
                style="display: block; width: 100%; background: #fff; max-width: 270px; padding: 25px;margin: 5px auto 0; box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);">

                <div class="receipt_header"
                    style="padding-bottom: 40px; border-bottom: 1px dashed #000; text-align: center;">
                    <h1 style="font-size: 20px; margin-bottom: 5px; text-transform: uppercase;">Receipt of Sale
                        <span style="display: block; font-size: 25px;">@{{settingData.company_name}}</span>
                    </h1>
                    <h2 style="font-size: 14px; color: #727070; font-weight: 300;">Address: @{{settingData.company_address}}
                        <span style="display: block;">Tel: @{{settingData.company_phone}}</span>
                    </h2>
                    <span>OrderNo: @{{ orderDetail.order_no }}</span></br></br>
                    <span>CashierNmae - {{getLoginUser()}}</span>
                </div>
                <div class="receipt_body" style=" margin-top: 25px;">
                    <div class="date_time_con" style="display: flex; justify-content: center; column-gap: 25px;">
                        <div class="date">@{{ convertDateFormat(orderDetail.created_at) }}</div>
                        <div class="time">@{{ convertTimeFormat(orderDetail.created_at) }}</div>
                    </div>
                    <div class="items" style="padding: 20px;  margin-top: 25px;">
                        <table style="width: 100%;">

                            <thead style="border-bottom: 1px dashed #000;">
                                <th style="text-align: left;">ITEM</th>
                                <th style="text-align: left;">QTY</th>
                                <th style="text-align: right;">PRICE</th>
                            </thead>
                            <tbody style="border-bottom: 1px dashed #000;">

                                <tr ng-repeat="item in orderDetail.order_detail" style="padding-top: 15px;">
                                    <td style="text-align: left;">@{{ item.item.name }}</td>
                                    <td style="text-align: left;">@{{ item.quantity }}</td>
                                    <td style="text-align: right;">@{{ item.sub_total }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="padding-top: 15px; text-align: left;">Total</td>
                                    <td style="padding-top: 15px; text-align: left;">
                                        @{{ qty }}
                                    </td>
                                    <td style="padding-top: 15px; text-align: right;">@{{ orderDetail.total_amount }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <p style="border-top: 1px dashed #000; padding-top: 10px; margin-top: 25px; text-align: center;">Thank You!
                </p>
            </div>
        </div>

    </div>
    <div style="text-align: center; margin: 20px 0 30px 0;">
        <button class="btn btn-secondary btn-lg" onclick="printInvoice()"><i class="fa fa-print"
                aria-hidden="true"></i>Print</button>
    </div>


    <script>
        function printInvoice() {
            var printContents = document.getElementById('order-detail').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script src="{{ asset('asset/js/page/order_detail.js?v=20240111') }}"></script>
@endsection
