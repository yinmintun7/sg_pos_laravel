@extends('layouts.backend.master')
@section('title', isset($user) ? 'User Update' : 'User Create')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ isset($user) ? 'User Update' : 'User Create' }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            @if (@isset($user))
                                <form class="" action="{{ route('updateUser') }}" method="POST" novalidate>
                                    <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                @else
                                    <form class="" action="{{ route('storeUser') }}" method="POST" novalidate>
                            @endif
                            @csrf
                            <div class="field item form-group">
                                <label for="usertype" class="col-form-label col-md-3 col-sm-3 label-align">User Type<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="usertype" name="usertype" class="select2_group form-control">
                                        <option value=""></option>
                                        <option value="admin"
                                            {{ old('usertype') == 'admin' || (isset($user) && $user->role == getAdminRole()) ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="cashier"
                                            {{ old('usertype') == 'cashier' || (isset($user) && $user->role == getCashierRole()) ? 'selected' : '' }}>
                                            Cashier
                                        </option>
                                    </select>

                                </div>
                                @if ($errors->has('usertype'))
                                    <span class="errormessage">{{ $errors->first('usertype') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="username" class="col-form-label col-md-3 col-sm-3  label-align">Username<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="name" class="form-control" name="username"
                                        value="{{ old('username', isset($user) ? $user->username : '') }}" />
                                </div>
                                @if ($errors->has('username'))
                                    <span class="errormessage">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="password" class="col-form-label col-md-3 col-sm-3  label-align">Password<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="password" class="form-control" name="password" {{-- value="{{ old('password', isset($user) ? $user->password : '') }}" --}} />
                                </div>
                                @if ($errors->has('password'))
                                    <span class="errormessage">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="confirm_password" class="col-form-label col-md-3 col-sm-3  label-align">Confirm
                                    Password<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input id="confirm_password" class="form-control" name="confirm_password"
                                        value="{{ old('confirm_password', isset($user) ? $user->confirm_password : '') }}" />
                                </div>
                                @if ($errors->has('confirm_password'))
                                    <span class="errormessage">{{ $errors->first('confirm_password') }}</span>
                                @endif
                            </div>
                            <div class="field item form-group">
                                <label for="Status" class="col-form-label col-md-3 col-sm-3  label-align">Status<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select id="Status" name = "status" class="select2_group form-control">
                                        <option value=""></option>
                                        <option value="0"
                                            {{ isset($user) && $user->status == '0' ? 'selected' : '' }}>Enable
                                        </option>
                                        <option value="1"
                                            {{ isset($user) && $user->status == '1' ? 'selected' : '' }}>Disable
                                        </option>
                                    </select>
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
    @include('layouts.backend.partial.footer_html_end')
@endsection
