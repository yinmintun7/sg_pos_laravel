@extends('layouts.backend.master')
@section('title', 'Setting List')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Setting List </h2>
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
                                            <th class="column-title">Company Name </th>
                                            <th class="column-title">Company Phone </th>
                                            <th class="column-title">Compnay Email </th>
                                            <th class="column-title">Compnay Logo </th>
                                            <th class="column-title">Status </th>
                                            <th class="column-title">Company Address </th>
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
                                        @if ($setting)
                                            <tr class="even pointer">
                                                <td class="a-center ">
                                                    <input type="checkbox" class="flat" name="table_records">
                                                </td>
                                                <td class=" ">{{ $setting->company_name }}</td>
                                                <td class=" ">{{ $setting->company_phone }}</td>
                                                <td class=" ">{{ $setting->company_email }}</td>
                                                <td class=" ">{{ $setting->company_address }}</td>
                                                <td class=" ">
                                                    @if ($setting->status == 0)
                                                        <span class="badge badge-primary">Enable</span>
                                                    @else
                                                        <span class="badge badge-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="">
                                                    <div class="" style="width: 100px; height: 100px;">
                                                        <img src="{{ asset('storage/upload/setting/' . $setting->company_logo) }}"
                                                            alt="" style="width: 100%; height: 100%;" />
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class = "row">
                                                        <div class="col-md-5">
                                                            <a href="{{ url('/sg-backend/setting/edit/') }}"
                                                                class="btn btn-info btn-md">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <form action="{{ url('/sg-backend/setting/delete') }}"
                                                                method="POST" id="setting-form-{{ $setting->id }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $setting->id }}">
                                                                <button type="button" class="btn btn-danger btn-md"
                                                                    onclick="confirmDelete('setting-form-{{ $setting->id }}')">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
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
