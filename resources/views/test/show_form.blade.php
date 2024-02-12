<!DOCTYPE html>
<html>
<style>
    input[type=text],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }

    div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }
</style>

<body>
    <h3>Using CSS to style an HTML Form</h3>

    <div>
        @if (@isset($setting))
            <form action="{{ route('UpdateForm') }}" method="POST">
                <input type="hidden" id="id" name="id" value="{{ $setting->id }}">
            @else
                <form action="{{ route('StoreForm') }}" method="POST">
        @endif
        @csrf
        <label for="fname">Company Name</label>
        <input type="text" id="fname" name="company_name" placeholder="Company Name"
            value="{{ old('company_name', isset($setting) ? $setting->company_name : '') }}">
        @if ($errors->has('company_name'))
            <span style="color:red">{{ $errors->first('company_name') }}</span>
        @endif
        <br />
        <label for="lname">Company Phone</label>
        <input type="text" id="lname" name="company_phone" placeholder="Company Phone"
            value="{{ old('company_phone', isset($setting) ? $setting->company_phone : '') }}">
        @if ($errors->has('company_phone'))
            <span style="color:red">{{ $errors->first('company_phone') }}</span>
        @endif
        <br />
        <br />
        <label for="lname">Company Email</label>
        <input type="text" id="lname" name="company_email" placeholder="Company Email"
            value="{{ old('company_email', isset($setting) ? $setting->company_email : '') }}">
        @if ($errors->has('company_email'))
            <span style="color:red">{{ $errors->first('company_email') }}</span>
        @endif
        <br />
        <br />
        <label for="lname">Company Adress</label>
        <input type="text" id="lname" name="company_address" placeholder="Company Adress"
            value="{{ old('company_address', isset($setting) ? $setting->company_address : '') }}">
        @if ($errors->has('company_address'))
            <span style="color:red">{{ $errors->first('company_address') }}</span>
        @endif
        <br />
        <br />
        <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>
