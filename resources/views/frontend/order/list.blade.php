@extends('layouts.frontend.master')
@section('title', 'Order List')
@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #86bc25;
            /* Set collapsed border for every td */
        }

        th {
            background-color: #262826;
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.12);
        }

        .disabled-row {

            /* Add your styles to visually indicate a disabled row */
            text-decoration: line-through;
            color: red;
            /* Example: reduce opacity for a disabled look */
        }
    </style>
    <div class="containder_fluid" ng-app="myApp" ng-controller="myCtrl" ng-init="init()">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h3 class="h3"><strong>Order Listing</strong></h3>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-11">
                        <table id="invoice">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Order Time</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="order in orderList" ng-class="{ 'disabled-row': order.status === 2}">

                                    <td>
                                        @{{ order.gid }}
                                        <span ng-if="order.status == 0" class="badge badge-primary">unpaid</span>
                                        <span ng-if="order.status == 1" class="badge badge-success">paid</span>
                                        <span ng-if="order.status == 2" class="badge badge-danger">cancel</span>
                                    </td>
                                    <td>@{{ order.order_time }}</td>
                                    <td>@{{ order.total_amount }}</td>
                                    <td>
                                        <span ng-if="order.status === 0"><button class="btn btn-info"
                                                ng-click="payOrder(order.id)">ToPay</button></span>
                                        <span ng-if="order.status === 1"><button class="btn btn-info">Paid</button></span>
                                        <button class="btn btn-primary" ng-if="order.status === 2"
                                            ng-click="OrderStatus(order,0)">Active</button>
                                        <button ng-if="order.status === 0" class="btn btn-danger"
                                            ng-click="OrderStatus(order,2)">Cancel</button>
                                        <button class="btn btn-info"
                                            ng-click="orderDetailPage(order.id)">ViewOrderDetail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script src="{{ asset('asset/js/page/order_list.js?v=202450108') }}"></script>
        @endsection
