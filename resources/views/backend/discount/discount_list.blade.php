@extends('layouts.backend.master')
@section('title', 'Discount List')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Discount List </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th>
                                                <input type="checkbox" id="check-all" class="flat">
                                            </th>
                                            <th class="column-title">Name </th>
                                            <th class="column-title">Discount </th>
                                            <th class="column-title">Start_date </th>
                                            <th class="column-title">End_date </th>
                                            <th class="column-title">Items </th>
                                            <th class="column-title">Status </th>
                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                            </th>
                                            <th class="bulk-actions" colspan="7">
                                                <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions
                                                    ( <span class="action-cnt"> </span> ) <i
                                                        class="fa fa-chevron-down"></i></a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($discounts as $discount)
                                            <tr class="even pointer">
                                                <td class="a-center ">
                                                    <input type="checkbox" class="flat" name="table_records">
                                                </td>
                                                <td class=" ">{{ $discount->name }}</td>
                                                <td class=" ">{{ $discount->discount_amount }}</td>
                                                <td class=" ">{{ changeFormatjfY($discount->start_date) }}</td>
                                                <td class=" ">{{ changeFormatjfY($discount->end_date) }}</td>
                                                <td class=" ">
                                                    @if ($discount->getDiscountItems() != null)
                                                        @foreach ($discount->getDiscountItems as $key => $discountitem)
                                                            {{ $discountitem->getItemByDiscountItem->name }}{{ $loop->last ? '' : ',' }}
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class=" ">
                                                    @if ($discount->status == 0)
                                                        <span class="badge badge-primary">Enable</span>
                                                    @else
                                                        <span class="badge badge-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="">
                                                    <div class = "row">
                                                        <div class="col-md-5">
                                                            <a href="{{ url('/sg-backend/discount/edit/') }}/{{ $discount->id }}"
                                                                class="btn btn-info btn-md">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <form action="{{ url('/sg-backend/discount/delete') }}"
                                                                method="POST" id="discount-form-{{ $discount->id }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $discount->id }}">
                                                                <button type="button" class="btn btn-danger btn-md"
                                                                    onclick="confirmDelete('discount-form-{{ $discount->id }}')">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $discounts->links('pagination::bootstrap-5') !!}
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
