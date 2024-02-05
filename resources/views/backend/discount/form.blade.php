@extends('layouts.backend.master')
@section('title', isset($discount) ? 'Discount Update' : 'Discount Create')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ isset($discount) ? 'Discount Update' : 'Discount Create' }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            @if (@isset($discount))
                                <form class="" action="{{ route('updateDiscount') }}" method="POST" novalidate>
                                    <input type="hidden" id="id" name="id" value="{{ $discount->id }}">
                                @else
                                    <form class="" action="{{ route('storeDiscount') }}" method="POST" novalidate>
                            @endif

                            @csrf

                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Discount
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="name"
                                        value="{{ old('name', isset($discount) ? $discount->name : '') }}" />
                                </div>
                                @if ($errors->has('name'))
                                    <span class="errormessage">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="discount_type" class="col-form-label col-md-3 col-sm-3  label-align">Discount
                                    Type<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <div style="margin-top:9px;">
                                        <input type="radio" id="percentage" name="discount_type" value="percentage"
                                            checked />
                                        <label for="percentage">Percentage</label>
                                        <input type="radio" id="cash" name="discount_type" value="cash" />
                                        <label for="cash">Cash</label>
                                    </div>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="amount" class="col-form-label col-md-3 col-sm-3  label-align"><span
                                        class = "discount_amount">Discount Percentage Amount</span><span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        value="" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="Status" class="col-form-label col-md-3 col-sm-3  label-align">Status<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="Status" name = "status" class="select2_group form-control">
                                        <option value=""></option>
                                        <option value="0"
                                            {{ isset($category) && $category->status == '0' ? 'selected' : '' }}>Enable
                                        </option>
                                        <option value="1"
                                            {{ isset($category) && $category->status == '1' ? 'selected' : '' }}>Disable
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="start_date" class="col-form-label col-md-3 col-sm-3  label-align">Start
                                    Date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control has-feedback-left" name="start_date"
                                        id="start_date" value="" aria-describedby="inputSuccess2Status">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="end_date" class="col-form-label col-md-3 col-sm-3  label-align">End Date<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control has-feedback-left" name="end_date"
                                        id="end_date" value="" aria-describedby="inputSuccess2Status2">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                                </div>
                            </div>

                            <div class="field item form-group">
                                <label for="Parent" class="col-form-label col-md-3 col-sm-3  label-align">Item<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    @foreach ($items as $item)
                                        <!-- <div class="row"> -->
                                        <div class="col-md-5">
                                            <input type="checkbox" class="flat" id="{{ $item->name }}"
                                                name="item[]" value="{{ $item->id }}" />
                                            <label for="{{ $item->name }}">{{ $item->name }}</label>
                                        </div>
                                        <!-- </div> -->
                                    @endforeach

                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="single_cal2"
                                    class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea id="description" required="required" class="form-control" name="description" value=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid">
                            <div class="form-group">
                                <div class="col-md-6 offset-md-3">
                                    <button type='submit' class="btn btn-primary">Submit</button>
                                    <button type='reset' class="btn btn-success">Reset</button>
                                    <input type="hidden" name="form_sub" value = "1" />
                                </div>
                            </div>
                        </div>
                        </form>
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
        $(document).ready(function() {
            $('input[name="discount_type"]').change(function() {
                const selectedValue = $('input[name="discount_type"]:checked').val();
                if (selectedValue == 'cash') {
                    $('.discount_amount').text('Discount Cash Amount');
                } else {
                    $('.discount_amount').text('Discount Percentage Amount');
                }
            });
        });
    </script>
    @include('layouts.backend.partial.footer_html_end')
@endsection
