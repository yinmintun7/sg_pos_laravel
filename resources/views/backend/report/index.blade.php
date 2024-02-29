@extends('layouts.backend.master')
@section('title', 'DailyReport')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>DailyReport</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
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
                                            <th class="column-title">Date</th>
                                            <th class="column-title">Amount</th>

                                        </tr>

                                    </thead>
                                    <tbody>
                                        <div class="row">
                                            <form method="GET">
                                                @if (request()->has('search'))
                                                    <input type="hidden" name="search" value="1">
                                                    <input type="hidden" name="start_date"
                                                        value="{{ request()->get('start_date') }}">
                                                    <input type="hidden" name="end_date"
                                                        value="{{ request()->get('end_date') }}">
                                                @endif
                                                <div class="field item form-group">
                                                    <label for="start_date"
                                                        class="col-form-label col-md-3 col-sm-3 label-align">Start Date<span
                                                            class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" class="form-control has-feedback-left"
                                                            name="start_date" id="start_date_report"
                                                            value="{{ request()->get('start_date') }}"
                                                            aria-describedby="inputSuccess2Status">
                                                        <span class="fa fa-calendar-o form-control-feedback left"
                                                            aria-hidden="true"></span>
                                                        <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                                    </div>
                                                </div>
                                                <div class="field item form-group">
                                                    <label for="end_date"
                                                        class="col-form-label col-md-3 col-sm-3 label-align">End Date<span
                                                            class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" class="form-control has-feedback-left"
                                                            name="end_date" id="end_date_report"
                                                            value="{{ request()->get('end_date') }}"
                                                            aria-describedby="inputSuccess2Status2">
                                                        <span class="fa fa-calendar-o form-control-feedback left"
                                                            aria-hidden="true"></span>
                                                        <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary" name="search"
                                                    formaction="{{ route('dailyReport') }}"><i class="fa fa-search"
                                                        aria-hidden="true"></i> Search</button>
                                                <button type="submit" class="btn btn-primary" name="download"
                                                    formaction="{{ route('dailyReportExcel') }}"><i
                                                        class="fa fa-cloud-download" aria-hidden="true"></i>
                                                    Download</button>
                                            </form>
                                        </div>
                                        @foreach ($result as $data)
                                            @if ($data->date == '')
                                                <tr class="even pointer">
                                                    <td class="a-center ">
                                                        <input type="checkbox" class="flat" name="table_records">
                                                    </td>
                                                    <td class=" ">Total</td>
                                                    <td class=" ">{{ $data->total }} </td>
                                                </tr>
                                            @elseif ($data->date != '')
                                                <tr class="even pointer">
                                                    <td class="a-center ">
                                                        <input type="checkbox" class="flat" name="table_records">
                                                    </td>
                                                    <td class=" ">{{ $data->date }}</td>
                                                    <td class=" ">{{ $data->amount }} </td>
                                                </tr>
                                            @endif
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
    @include('layouts.backend.partial.footer_start')
    @include('layouts.backend.partial.footer_end')
    @include('layouts.backend.partial.footer_html_end')
@endsection
