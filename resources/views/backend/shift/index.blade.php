@extends('layouts.backend.master')
@section('title', 'Admin Dashboard')
@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Shift List </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-lg btn-round"
                                onclick="openTime('{{ url('/sg-backend/shift/start') }}')"
                                style="display:{{ $shift_open ? 'none' : 'inline' }}">
                                <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                <span class="glyphicon-class">Open-Shift</span>
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg btn-round"
                                onclick="closeTime('{{ url('/sg-backend/shift/end') }}')"
                                style="display:{{ $shift_open ? 'inline' : 'none' }}">
                                <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                <span class="glyphicon-class">Close-Shift</span>
                            </button>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th>
                                                <input type="checkbox" id="check-all" class="flat">
                                            </th>
                                            <th class="column-title">Start Date Time </th>
                                            <th class="column-title">End Date Time </th>
                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                            </th>
                                            <th class="bulk-actions" colspan="7">
                                                <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span
                                                        class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($shift_list as $shift )
                                        <tr class="even pointer">
                                            <td class="a-center ">
                                                <input type="checkbox" class="flat" name="table_records">
                                            </td>
                                            <td class=" ">{{$shift ->start_date_time}}</td>
                                            <td class=" ">{{$shift ->end_date_time}}</td>
                                            <td class="last">
                                                <a href="javascript:void(0)" class="btn btn-info btn-xs"
                                                    style="width:50%;"><i class="fa fa-pencil"></i> View Order List </a>
                                                <!-- <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="confirmDelete('<')">
                                                        <i class="fa fa-trash-o"></i> Delete
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs"><i class="fa fa-arrows"></i>Move</a> -->

                                                <!-- </td> -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $shift_list->links('pagination::bootstrap-5') !!}
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
    <script>
        function openTime(startUrl) {
            Swal.fire({
                title: 'Open Shift Confirmation',
                text: 'Are you sure you want to Open?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Swal.fire('Opened!', 'Open time has been confirmed.', 'success');
                    window.location.href = startUrl;
                }
            });
        }

        function closeTime(endUrl) {
            Swal.fire({
                title: 'Close Shift Confirmation',
                text: 'Are you sure you want to Close?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    //Swal.fire('Closed!', 'Close time has been confirmed.', 'success');
                    window.location.href = endUrl;
                }
            });
        }

        $(document).ready(function() {
            @if (session('shift_start'))
                new PNotify({
                    title: 'Regular Success',
                    text: '{{ session('shift_start') }}',
                    type: 'success',
                    styling: 'bootstrap3'
                });
            @endif
            @if ($errors->has('shift_error'))
                new PNotify({
                    title: 'Oh No!',
                    text: '{{ $errors->first('shift_error') }}',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            @endif
            @if (session('shift_end'))
                new PNotify({
                    title: 'Regular Success',
                    text: '{{ session('shift_end') }}',
                    type: 'success',
                    styling: 'bootstrap3'
                });
            @endif
        });
    </script>
    @include('layouts.backend.partial.footer_html_end')
@endsection
