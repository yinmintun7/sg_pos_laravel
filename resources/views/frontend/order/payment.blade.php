@extends('layouts.frontend.master')
@section('title', 'Payment')
@section('content')
    <div class="wrapper">
        <div class="container-fluid receipt" ng-app="myApp" ng-controller="myCtrl" ng-init="orderDetail({{ $id }})">
            <div id="order-detail" ng-hide="hideOrderDetail">
                <div class="container"
                    style="display: block; width: 100%; background: #fff; max-width: 270px; padding: 25px;margin: 5px auto 0; box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);">

                    <div class="receipt_header"
                        style="padding-bottom: 40px; border-bottom: 1px dashed #000; text-align: center;">
                        <h1 style="font-size: 20px; margin-bottom: 5px; text-transform: uppercase;">Receipt of Sale
                            <span style="display: block; font-size: 25px;">SG</span>
                        </h1>
                        <h2 style="font-size: 14px; color: #727070; font-weight: 300;">Address: SoftGuide, 575B
                            <span style="display: block;">Tel: +1 012 345 67 89</span>
                        </h2>
                        <span>OrderNo: @{{ orderDetail.order_detail.item.order_no }}</span>
                    </div>
                    <div class="receipt_body" style=" margin-top: 25px;">
                        <div class="date_time_con" style="display: flex; justify-content: center; column-gap: 25px;">
                            <div class="date">@{{ orderDetail.date }}</div>
                            <div class="time">@{{ orderDetail.time }}</div>
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
                                        <td style="padding-top: 15px; text-align: right;">
                                            @{{ orderDetail.total_amount }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <p style="border-top: 1px dashed #000; padding-top: 10px; margin-top: 25px; text-align: center;">Thank
                        You!</p>
                </div>
            </div>

            <div class="row cmn-ttl cmn-ttl2">
                <div class="container">
                    <div class="row">
                        <input type="hidden" class="void-value" id="" />
                        <input type="hidden" class="void-type" id="" />
                        <div class="col-lg-4 col-md-5 col-sm-6 col-6">
                            <h3>Order no : @{{ orderDetail.order_no }}
                            </h3>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-6 col-6 receipt-btn">
                            <button class="btn print-modal" id="printInvoice" onclick="printInvoice()">
                                <img src="{{ asset('asset/images/frontend/payment/print_img.png') }}" alt="Print Image"
                                    class="heightLine_06">
                            </button>

                            <a class="btn" href="" onclick="history.back()">
                                <img src="{{ asset('asset/images/frontend/payment/previous_img.png') }}" alt="Previous"
                                    class="heightLine_06">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-6">
                            <div class="table-responsive">
                                <table class="table receipt-table">
                                    <tr>
                                        <td colspan="2">Sub Total</td>
                                        <td colspan="2"></td>
                                        </td>
                                        <td colspan="2">@{{ orderDetail.total_amount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-gray">ITEM</td>
                                        <td colspan="2" class="bg-gray">QTY</td>
                                        <td colspan="2" class="bg-gray bg-gray-price">PRICE</td>
                                    </tr>
                                    <tr ng-repeat="item in orderDetail.order_detail">
                                        <td colspan="2" style="text-align: left;">@{{ item.item.name }}</td>
                                        <td colspan="2" style="text-align: center;">@{{ item.quantity }}</td>
                                        <td colspan="2" style="text-align: right;">@{{ item.sub_total }}</td>
                                    </tr>
                                </table>
                            </div><!-- table-responsive -->

                            <h3 class="receipt-ttl"></h3>
                            <div class="table-responsive">
                                <table class="table receipt-table" id="invoice-table">
                                    <tr class="before-tr" style="height: 32px;">
                                        <td colspan="2" class="bl-data"></td>
                                    </tr>
                                    <tr class="tender pointer" ng-repeat="kyat in kyats"
                                        ng-class="{'bg-grey-selected':selectIndex.indexOf(kyat.index) !== -1}">
                                        <td>

                                        </td>
                                        <td ng-click="selectedCash(kyat.index)">@{{ kyat.total_cash }}MMK</td>
                                    </tr>
                                    <tr>
                                        <td>BALANCE</td>
                                        <td class="balance">@{{ balance }}</td>
                                    </tr>
                                    <tr>
                                        <td>REFUND</td>
                                        <td class="change">
                                            @{{ refund }}
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- table-responsive -->
                            <div class="row receipt-btn02">
                                <div class="col-md-6 col-sm-6 col-6"><a class="btn btn-primary view-btn"
                                        href="/order-detail-page/@{{ orderDetail.id }}">VIEW DETAILS</a></div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8 col-6">
                            <div class="row">
                                <div class="col-md-12 list-group" id="myList" role="tablist">
                                    <a class="list-group-item list-group-item-action heightLine_05 active"
                                        data-toggle="list" href="#home" role="tab" id="payment-cash">
                                        <span class="receipt-type cash-img"></span><span class="receipt-txt">Cash</span>
                                    </a>
                                    <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list"
                                        href="#profile" role="tab" id="payment-card">
                                        <span class="receipt-type card-img"></span><span class="receipt-txt">Card</span>
                                    </a>
                                    <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list"
                                        href="#messages" role="tab" id="payment-voucher">
                                        <span class="receipt-type voucher-img"></span><span
                                            class="receipt-txt">Voucher</span>
                                    </a>
                                    <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list"
                                        href="#settings" role="tab" id="payment-nocollection">
                                        <span class="receipt-type collection-img"></span><span class="receipt-txt">No
                                            Collection</span>
                                    </a>
                                    <a class="list-group-item list-group-item-action heightLine_05" data-toggle="list"
                                        href="#settings" role="tab" id="payment-loyalty">
                                        <span class="receipt-type loyality-img"></span><span
                                            class="receipt-txt">Loyalty</span>
                                    </a>
                                </div> <!-- list-group -->
                                <div class="col-md-12">
                                    <div class="tab-content row">
                                        <div class="tab-pane active" id="home" role="tabpanel">
                                            <button class="btn heightLine_04 cash-payment" id="CASH"
                                                ng-disabled="disable"><span
                                                    class="extra-cash"></span><span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH50"
                                                ng-disabled="disable" ng-click="payCash(50)"><span
                                                    class="money">50</span> <span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH100"
                                                ng-disabled="disable" ng-click="payCash(100)"><span
                                                    class="money">100</span><span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH200"
                                                ng-disabled="disable" ng-click="payCash(200)"><span
                                                    class="money">200</span><span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH500"
                                                ng-disabled="disable" ng-click="payCash(500)"> <span
                                                    class="money">500</span> <span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH1000"
                                                ng-disabled="disable" ng-click="payCash(1000)"><span
                                                    class="money">1000</span><span>Kyats</span></button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH5000"
                                                ng-disabled="disable" ng-click="payCash(5000)"><span
                                                    class="money">5000</span><span>Kyats</span> </button>
                                            <button class="btn heightLine_04 cash-payment" id="CASH10000"
                                                ng-disabled="disable" ng-click="payCash(10000)"> <span
                                                    class="money">10000</span><span>Kyats</span></button>
                                        </div>
                                        <div class="tab-pane" id="profile" role="tabpanel">
                                            <button class="btn heightLine_05 mpu-type agd-mpu card-payment"
                                                id="MPU_AGD"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">AGD</span></button>
                                            <button class="btn heightLine_05 mpu-type kbz-mpu card-payment"
                                                id="MPU_KBZ"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">KBZ</span></button>
                                            <button class="btn heightLine_05 mpu-type uab-mpu card-payment"
                                                id="MPU_UAB"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">UAB</span></button>
                                            <button class="btn heightLine_05 mpu-type mob-mpu card-payment"
                                                id="MPU_MOB"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">MOB</span></button>
                                            <button class="btn heightLine_05 mpu-type chd-mpu card-payment"
                                                id="MPU_CHD"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">CHD</span></button>

                                            <button class="btn heightLine_05 mpu-type kbz-visa card-payment"
                                                id="VISA_KBZ"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">KBZ</span></button>
                                            <button class="btn heightLine_05 mpu-type cb-visa card-payment"
                                                id="VISA_CB"><span class="receipt-type cash-img"></span><span
                                                    class="receipt-txt">CB</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-cal col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 payment-show">
                                            <p class="amount-quantity" style="min-height: 33px;">@{{ selectQuantity }}
                                            </p>
                                        </div>
                                        <div class="col-md-12 receipt-btn3">
                                            <button class="btn quantity" id="1"
                                                ng-click="clickValue(1)">1</button>
                                            <button class="btn quantity" id="2"
                                                ng-click="clickValue(2)">2</button>
                                            <button class="btn quantity" id="3"
                                                ng-click="clickValue(3)">3</button>
                                            <button class="btn quantity" id="4"
                                                ng-click="clickValue(4)">4</button>
                                            <button class="btn quantity" id="5"
                                                ng-click="clickValue(5)">5</button>
                                            <button class="btn quantity ml-2" id="6"
                                                ng-click="clickValue(6)">6</button>
                                            <button class="btn quantity" id="7"
                                                ng-click="clickValue(7)">7</button>
                                            <button class="btn quantity" id="8"
                                                ng-click="clickValue(8)">8</button>
                                            <button class="btn quantity" id="9"
                                                ng-click="clickValue(9)">9</button>
                                            <button class="btn quantity" id="0"
                                                ng-click="clickValue(0)">0</button>
                                        </div>
                                        <div class="col-md-12 receipt-btn4">
                                            <button class="btn btn-primary void-btn"
                                                id = 'void-item'ng-click="voidSelect()">VOID <i
                                                    class="fa fa-trash-alt"></i></button>
                                            <button class="btn clear-input-btn" ng-click="clear()">CLEAR INPUT</button>
                                            <button class="btn btn-primary foc-btn" ng-click="payOrder()"
                                                ng-disabled="topaydisable">To Pay</button>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!-- col-md-8 -->
                    </div>
                </div>
            </div>
        </div><!-- container-fluid -->
    </div><!-- wrapper -->
    <!--
                                                                                        print template here
                                                                                    {{-- @include('cashier.invoice.payment_print') --}}
                                                                                    -->
    <!-- item print her
                                                                                    {{-- @include('cashier.invoice.items_list') --}}
                                                                                -->
    <script src="{{ asset('asset/js/page/payment.js?v=20240215') }}"></script>
@endsection
