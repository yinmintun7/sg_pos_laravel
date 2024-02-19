@extends('layouts.frontend.master')
@section('title', 'Order')
@section('content')

    <body class="nav-md">
        <style>
            body {
                background-color: #fff;
                /* Set background color to white */
            }

            .center-container {
                display: flex;
                flex-direction: column;
                /* Arrange items in a column */
                justify-content: center;
                align-items: center;
                height: 100vh;
                /* Full height of the viewport */
                text-align: center;
                /* Center align the text within the container */
            }

            .centered-image {
                max-width: 100%;
                max-height: 100%;
                height: auto;
                margin-bottom: 20px;
                /* Add bottom margin for spacing between image and text */
            }

            h2 {
                color: #333;
                /* Set text color to dark gray */
                font-size: 24px;
                /* Set font size */
            }
        </style>

        <!-- page content -->
        <div class="container center-container">
            <img src="{{ asset('asset/images/shift_close_img/shift9.png') }}" alt="Centered Image" class="centered-image">
            <h2>Shift is closing cannot buy or sell!</h2>
            <button class="btn btn-lg btn-success"><a href="/login">OK</a></button>
        </div>
        <!-- /page content -->

    @endsection
