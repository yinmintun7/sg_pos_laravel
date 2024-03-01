@extends('layouts.backend.master')
@section('title', 'Admin Dashboard')
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="row">
            <!-- bar chart -->
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>WeeklySaleGraph</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="graph_bar" style="width:100%; height:280px;"></div>
                    </div>
                </div>
            </div>
            <!-- /bar charts -->
            <!-- line graph -->
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>MonthlySaleGraph</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- /line graph -->
        </div>
    </div>
    <!-- /page content -->
    @include('layouts.backend.partial.footer_start')
    @include('layouts.backend.partial.footer_end')
    <!-- java script herr -->
    @include('layouts.backend.partial.footer_html_end')
@endsection
