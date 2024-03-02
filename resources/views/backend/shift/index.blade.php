@extends('layouts.backend.master')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="right_col" role="main">
        <div class="row" style="display: block;">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Shift List </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                        <form action="{{ url('/sg-backend/shift/start') }}" method="POST" id="start">
                            @csrf
                            <button type="button" class="btn btn-primary btn-lg btn-round" onclick="confirmShift('start')"
                                style="display:{{ $shift_open ? 'none' : 'inline' }}">
                                <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                <span class="glyphicon-class">Open-Shift</span>
                            </button>
                        </form>

                        <form action="{{ url('/sg-backend/shift/end') }}" method="POST" id="end">
                            @csrf
                            <button type="button" class="btn btn-secondary btn-lg btn-round" onclick="confirmShift('end')"
                                style="display:{{ $shift_open ? 'inline' : 'none' }}">
                                <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                <span class="glyphicon-class">Close-Shift</span>
                            </button>
                        </form>

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

                                    @foreach ($shift_list as $shift)
                                        <tr class="even pointer">
                                            <td class="a-center ">
                                                <input type="checkbox" class="flat" name="table_records">
                                            </td>
                                            <td class=" ">{{ $shift->start_date_time }}</td>
                                            <td class=" ">{{ $shift->end_date_time }}</td>
                                            <td class="last">
                                                <a href="{{url('/sg-backend/shift/order-list-page')}}/{{ $shift->id }}" class="btn btn-info btn-xs"
                                                    style="width:50%;"><i class="fa fa-pencil"></i> View Order List </a>
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
    @include('layouts.backend.partial.footer_start')
    @include('layouts.backend.partial.footer_end')
    <!-- java script herr -->
    <script>
        function confirmShift(statement) {
            var title = (statement == 'start') ? 'Open Shift Confirmation' : 'Close Shift Confirmation';
            var text = (statement == 'start') ? 'Are you sure you want to Open?' : 'Are you sure you want to Close?';
            Swal.fire({
                title: title,
                text: text,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById(statement);
                    form.submit();
                }
            });
        }
    </script>
    @include('layouts.backend.partial.footer_html_end')
@endsection
