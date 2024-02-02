@extends('layouts.backend.master')
@section('title', isset($category) ? 'Category Update' : 'Category Create')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ isset($category) ? 'Category Update' : 'Category Create' }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            @if (@isset($category))
                                <form class="" action="{{ route('updateCategory') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    <input type="hidden" id="id" name="id" value="{{ $category->id }}">
                                @else
                                    <form class="" action="{{ route('storeCategory') }}" method="POST"
                                        enctype="multipart/form-data" novalidate>
                            @endif

                            @csrf
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Category
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="name"
                                        value="{{ old('name', isset($category) ? $category->name : '') }}" />
                                </div>
                                @if ($errors->has('name'))
                                    <span class="errormessage">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="field item form-group">
                                <label for="Parent"
                                    class="col-form-label col-md-3 col-sm-3  label-align">Parent-Category<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="Parent" name = "parent_id" class="select2_group form-control">
                                        <!-- <optgroup label="Pacific Time Zone"> -->
                                        <option value="" disabled>SELECT ONE</option>
                                        <option value="0"
                                            {{ isset($category) && $category->parent_id == '0' ? 'selected' : '' }}>
                                            parent_name</option>

                                        {{ getParentCategory(old('parent_id', isset($category) ? $category->parent_id : ''), ['item' => false, 'category' => true]) }}

                                        <!-- </optgroup> -->
                                    </select>
                                    @if ($errors->has('parent_id'))
                                        <span class="errormessage">{{ $errors->first('parent_id') }}</span>
                                    @endif
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
                                <label for= "image" class="col-form-label col-md-3 col-sm-3  label-align">Category
                                    Image<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <div id="previous_wrapper" style="display: {{ isset($category) ? 'none' : 'block' }}">

                                        <label class="chooseFile" for="upload" onclick = "fileInput()">Upload</label>
                                    </div>
                                    <div id="previous_wrapper-img"
                                        style="display: {{ isset($category) ? 'block' : 'none' }}">
                                        <div class="vertical-center">
                                            <img src="{{ isset($category) ? asset('storage/upload/category/' . $category->id . '/' . $category->image) : '' }}"
                                                id="image" alt="" style="width:100%;">
                                            <label class="chooseFile" for="upload" onclick = "fileInput()">Upload</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('image'))
                                    <span class="errormessage">{{ $errors->first('image') }}</span>
                                @endif
                            </div>
                        </div>
                        <input class="hide" type="file" id="fileInput" name="image" onchange = 'previewImage(this)'>
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
                    $('#image').attr('src', e.target.result);
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
