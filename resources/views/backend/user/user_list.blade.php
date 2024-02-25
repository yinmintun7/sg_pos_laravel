@extends('layouts.backend.master')
@section('title', 'User List')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>User List </h2>
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
                                            <th class="column-title">Username </th>
                                            <th class="column-title">Role</th>
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
                                        @foreach ($users as $user)
                                            <tr class="even pointer">
                                                <td class="a-center ">
                                                    <input type="checkbox" class="flat" name="table_records">
                                                </td>
                                                <td class=" ">{{ $user->username }}</td>
                                                <td class=" ">{{ $user->role_name }}</td>
                                                <td class=" ">
                                                    @if ($user->status == 0)
                                                        <span class="badge badge-primary">Enable</span>
                                                    @else
                                                        <span class="badge badge-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="last">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <a href="{{ url('/sg-backend/user/edit/') }}/{{ $user->id }}"
                                                                class="btn btn-info btn-xs">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <form action="{{ url('/sg-backend/user/delete') }}"
                                                                method="POST" id="user-form-{{ $user->id }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $user->id }}">
                                                                <button type="button" class="btn btn-danger btn-xs"
                                                                    onclick="confirmDelete('user-form-{{ $user->id }}')">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $users->links('pagination::bootstrap-5') !!}
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
