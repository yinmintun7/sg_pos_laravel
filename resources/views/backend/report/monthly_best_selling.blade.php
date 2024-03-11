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
                                            <form method="GET">
                                                @if (request()->has('search'))
                                                    <input type="hidden" name="search" value="1">
                                                    <input type="hidden" name="month"
                                                        value="{{ request()->get('month') }}">
                                                @endif
                                                <div class="field item form-group">
                                                    <label for="demo-2"
                                                        class="col-form-label col-md-3 col-sm-3 label-align">Select
                                                        Month<span class="required">*</span></label>
                                                    <input type="text" id="demo-2" name="month" />
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="search"
                                                    formaction="{{ route('monthlyBestSellingList') }}"><i
                                                        class="fa fa-search" aria-hidden="true"></i> Search</button>
                                                <button type="submit" class="btn btn-primary" name="download"
                                                    formaction="{{ route('monthlyBestSellingExcel') }}"><i
                                                        class="fa fa-cloud-download" aria-hidden="true"></i>
                                                    Download</button>
                                            </form>
                                        </div>
                                        {{-- <div class="row">
                                            <a href="{{ url('/sg-backend/report/monthly/best-selling-excel') }}"
                                                class="btn btn-primary" name="download">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                DownloadExcel
                                            </a>
                                        </div>
                                        <h2>Select Month</h2>
                                        <input type="text" id="demo-2" /> --}}
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
    <script src="{{ URL::asset('asset/js/jquery-1.11.2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('asset/css/jquery-ui.css') }}">
    <script src="{{ URL::asset('asset/js/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('asset/js/monthpicker.js') }}"></script>
    <script>
        $("#demo-2").monthpicker({
            pattern: "yyyy-mm",
            selectedYear: 2024,
            startYear: 1900,
            finalYear: 2212,
        });
        var options = {
            selectedYear: 2024,
            startYear: 2008,
            finalYear: 2018,
            openOnFocus: false, // Let's now use a button to show the widget
        };
    </script>
    {{-- <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(["_setAccount", "UA-36251023-1"]);
        _gaq.push(["_setDomainName", "jqueryscript.net"]);
        _gaq.push(["_trackPageview"]);
        (function() {
            var ga = document.createElement("script");
            ga.type = "text/javascript";
            ga.async = true;
            ga.src =
                ("https:" == document.location.protocol ?
                    "https://ssl" :
                    "http://www") + ".google-analytics.com/ga.js";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script> --}}
    @include('layouts.backend.partial.footer_html_end')
@endsection
