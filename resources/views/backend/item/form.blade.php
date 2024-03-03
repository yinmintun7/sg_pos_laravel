@extends('layouts.backend.master')
@section('title', isset($item) ? 'Item Update' : 'Item Create')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ isset($item) ? 'Item Update' : 'Item Create' }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        @if (@isset($item))
                            <div>
                                <button class="btn btn-sm btn-secondary">
                                    <a href="/sg-backend/item/list" style="color: white;">Back</a>
                                </button>
                            </div>
                        @endif
                        <div class="">
                            @if (@isset($item))
                                <form class="" action="{{ route('updateItem') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                                @else
                                    <form class="" action="{{ route('storeItem') }}" method="POST"
                                        enctype="multipart/form-data" novalidate>
                            @endif
                            @csrf
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Item
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="name"
                                        value="{{ old('name', isset($item) ? $item->name : '') }}" />
                                </div>
                                @if ($errors->has('name'))
                                    <span class="errormessage">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="field item form-group">
                                <label for="category_id" class="col-form-label col-md-3 col-sm-3  label-align">Category<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="category_id" name = "category_id" class="select2_group form-control">
                                        <!-- <optgroup label="Pacific Time Zone"> -->
                                        <option value=""></option>
                                        {{-- <option value="0"
                                            {{ isset($item) && $item->category_id == '0' ? 'selected' : '' }}>Category
                                        </option> --}}

                                        {{ getParentCategory(old('category_id', isset($item) ? $item->category_id : ''), ['item' => true, 'category' => false]) }}

                                        <!-- </optgroup> -->
                                    </select>

                                </div>
                                @if ($errors->has('category_id'))
                                    <span class="errormessage">{{ $errors->first('category_id') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="price" class="col-form-label col-md-3 col-sm-3  label-align">Price
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="number" id="price" class="form-control" name="price"
                                        value="{{ old('price', isset($item) ? $item->price : '') }}" />
                                </div>
                                @if ($errors->has('price'))
                                    <span class="errormessage">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="quantity" class="col-form-label col-md-3 col-sm-3  label-align">Quantity
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="number" id="quantity" class="form-control" name="quantity"
                                        value="{{ old('quantity', isset($item) ? $item->quantity : '') }}" />
                                </div>
                                @if ($errors->has('quantity'))
                                    <span class="errormessage">{{ $errors->first('quantity') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="Status" class="col-form-label col-md-3 col-sm-3  label-align">Status<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="Status" name = "status" class="select2_group form-control">
                                        <option value=""></option>
                                        <option value="0"
                                            {{ isset($item) && $item->status == '0' ? 'selected' : '' }}>Enable</option>
                                        <option value="1"
                                            {{ isset($item) && $item->status == '1' ? 'selected' : '' }}>Disable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for= "image" class="col-form-label col-md-3 col-sm-3  label-align">Item
                                    Image<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <div id="previous_wrapper" style="display: {{ isset($item) ? 'none' : 'block' }}">

                                        <label class="chooseFile" for="upload" onclick = "fileInput()">Upload</label>
                                    </div>
                                    <div id="previous_wrapper-img" style="display: {{ isset($item) ? 'block' : 'none' }}">
                                        <div class="vertical-center">
                                            <img src="{{ isset($item) ? asset('storage/upload/item/' . $item->id . '/' . $item->image) : '' }}"
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
                        <input class="hide" type="file" id="fileInput" name="image"
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
