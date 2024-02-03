@extends('layouts.backend.master')
@section('title', 'Item List')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Item List </h2>
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
                                            <th class="column-title">Category </th>
                                            <th class="column-title">Price </th>
                                            <th class="column-title">Quantity </th>
                                            <th class="column-title">CodeNo </th>
                                            <th class="column-title">Status </th>
                                            <th class="column-title">Image </th>
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
                                        @foreach ($items as $item)
                                            <tr class="even pointer">
                                                <td class="a-center ">
                                                    <input type="checkbox" class="flat" name="table_records">
                                                </td>
                                                <td class=" ">{{ $item->name }}</td>
                                                <td class=" ">{{ $item->category_name }}</td>
                                                <td class=" ">{{ $item->price}}</td>
                                                <td class=" ">{{ $item->quantity}}</td>
                                                <td class=" ">{{ $item->code_no}}</td>
                                                <td class=" ">
                                                    @if ($item->status == 0)
                                                        <span class="badge badge-primary">Enable</span>
                                                    @else
                                                        <span class="badge badge-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="">
                                                    <div class="" style="width: 100px; height: 100px;">
                                                        <img src="{{ asset('storage/upload/item/' . $item->id . '/' . $item->image) }}"
                                                            alt="" style="width: 100%; height: 100%;" />
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class = "row">
                                                        <div class="col-md-6">
                                                            <a href="{{ url('/sg-backend/item/edit/') }}/{{ $item->id }}" class="btn btn-info btn-md">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <form action="{{ url('/sg-backend/item/delete') }}" method="POST" id="item-form">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                                <button type="button" class="btn btn-danger btn-md" onclick="confirmDelete('item-form')">
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
                                {!! $items->links('pagination::bootstrap-5') !!}
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
