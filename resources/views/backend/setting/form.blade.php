@extends('layouts.backend.master')
@section('title', isset($setting) ? 'Setting Update' : 'Setting Create')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ isset($setting) ? 'Setting Update' : 'Setting Create' }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            @if (@isset($setting))
                                <form class="" action="{{ route('updateSetting') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    <input type="hidden" id="id" name="id" value="{{ $setting->id }}">
                                @else
                                    <form class="" action="{{ route('storeSetting') }}" method="POST"
                                        enctype="multipart/form-data" novalidate>
                            @endif
                            @csrf
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Company
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="company_name"
                                        value="{{ old('company_name', isset($setting) ? $setting->company_name : '') }}" />
                                </div>
                                @if ($errors->has('company_name'))
                                    <span class="errormessage">{{ $errors->first('company_name') }}</span>
                                @endif
                            </div>

                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Company
                                    Phone<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="company_phone"
                                        value="{{ old('company_phone', isset($setting) ? $setting->company_phone : '') }}" />
                                </div>
                                @if ($errors->has('company_phone'))
                                    <span class="errormessage">{{ $errors->first('company_phone') }}</span>
                                @endif
                            </div>

                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Company
                                    Email<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="company_email"
                                        value="{{ old('company_email', isset($setting) ? $setting->company_email : '') }}" />
                                </div>
                                @if ($errors->has('company_email'))
                                    <span class="errormessage">{{ $errors->first('company_email') }}</span>
                                @endif
                            </div>


                            <div class="field item form-group">
                                <label for="Status" class="col-form-label col-md-3 col-sm-3  label-align">Status<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="Status" name = "status" class="select2_group form-control">
                                        <option value=""></option>
                                        <option value="0"
                                            {{ isset($setting) && $setting->status == '0' ? 'selected' : '' }}>Enable
                                        </option>
                                        <option value="1"
                                            {{ isset($setting) && $setting->status == '1' ? 'selected' : '' }}>Disable
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for= "image" class="col-form-label col-md-3 col-sm-3  label-align">Category
                                    Image<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <div id="previous_wrapper" style="display: {{ isset($setting) ? 'none' : 'block' }}">

                                        <label class="chooseFile" for="upload" onclick = "fileInput()">Upload</label>
                                    </div>
                                    <div id="previous_wrapper-img"
                                        style="display: {{ isset($setting) ? 'block' : 'none' }}">
                                        <div class="vertical-center">
                                            <img src="{{ isset($setting) ? asset('storage/upload/setting/' . $setting->company_logo) : '' }}"
                                                id="company_logo" alt="" style="width:100%;">
                                            <label class="chooseFile" for="upload" onclick = "fileInput()">Upload</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('company_logo'))
                                    <span class="errormessage">{{ $errors->first('company_logo') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Company Address
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="company_address" class="form-control" name="company_address"
                                        value="{{ old('company_address', isset($setting) ? $setting->company_address : '') }}" />
                                </div>
                                @if ($errors->has('company_address'))
                                    <span class="errormessage">{{ $errors->first('company_address') }}</span>
                                @endif
                            </div>

                        </div>
                        <input class="hide" type="file" id="fileInput" name="company_logo"
                            onchange = 'previewImage(this)'>
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
    <script>
        function fileInput() {
            $('#fileInput').click();
        }

        function previewImage(input) {
            const file = input.files[0];
            let fileExtension = file.name.split('.').pop();
            let allow_file_type = ['jpg', 'jpeg', 'svg', 'png', 'gif'];
            if (fileExtension && allow_file_type.includes(fileExtension.toLowerCase())) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#company_logo').attr('src', e.target.result);
                };
                $('#previous_wrapper').hide();
                $('#previous_wrapper-img').show();
                reader.readAsDataURL(file);
            } else {
                console.log('File extension is invalid:', fileExtension);
            }
        }
    </script>
    <!-- java script herr -->
    <script>
        $(document).ready(function() {
            @if ($errors->has('category_error'))
                new PNotify({
                    title: 'Oh No!',
                    text: '{{ $errors->first('category_error') }}',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            @endif
        });
    </script>
    @include('layouts.backend.partial.footer_html_end')
@endsection
