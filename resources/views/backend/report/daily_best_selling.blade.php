@extends('layouts.backend.master')
@section('title', 'DailyBestSelling')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>DailyBestSelling</h2>
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
                                            <th class="column-title">ItemName</th>
                                            <th class="column-title">Quantity</th>
                                            <th class="column-title">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div class="row">
                                            <a href="{{ url('/sg-backend/report/daily/best-selling-excel') }}"
                                                class="btn btn-primary" name="download">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                DownloadExcel
                                            </a>
                                        </div>
                                        @foreach ($result as $data)
                                            <tr class="even pointer">
                                                <td class=" ">{{ $data->name }}</td>
                                                <td class=" "> {{ $data->total_quantity }}</td>
                                                <td class=" "> {{ $data->total_sub_total }}</td>
                                            </tr>
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
