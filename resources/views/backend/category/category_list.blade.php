@extends('layouts.backend.master')
@section('title', 'Category Form')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row" style="display: block;">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Category List </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th>
                                                <input type="checkbox" id="check-all" class="flat">
                                            </th>


                                            <th class="column-title">Name </th>
                                            <th class="column-title">Parent Category </th>
                                            <th class="column-title">Status </th>
                                            <th class="column-title">Image </th>
                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                            </th>
                                            <th class="bulk-actions" colspan="7">
                                                <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions
                                                    ( <span class="action-cnt"> </span> ) <i
                                                        class="fa fa-chevron-down"></i></a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr class="even pointer">
                                                <td class="a-center ">
                                                    <input type="checkbox" class="flat" name="table_records">
                                                </td>
                                                <td class=" ">{{ $category->name }}</td>
                                                <td class=" ">{{ $category->parent_name }}</td>
                                                <td class=" ">
                                                    @if ( $category->status == 0)
                                                        <span class="badge badge-primary">Enable</span>
                                                    @else
                                                        <span class="badge badge-secondary">Disable</span>
                                                    @endif
                                                </td>
                                                <td class="">
                                                    <div class="" style="width: 100px; height: 100px;">
                                                        <img src="{{ asset('storage/upload/category/' . $category->id . '/' . $category->image) }}" alt=""
                                                            style="width: 100%; height: 100%;" />
                                                    </div>
                                                </td>

                                                <td class="last">
                                                    <a href="{{ url('/sg-backend/category/edit/')}}/{{ $category->id }}" class="btn btn-info btn-xs"><i
                                                            class="fa fa-pencil"></i> Edit </a>
                                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs"
                                                        onclick="confirmDelete('{{ url('/sg-backend/category/delete/')}}/{{ $category->id }}')">
                                                        <i class="fa fa-trash-o"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- {!! $categories->links('pagination::bootstrap-5') !!} --}}
                            </div>
                        </div>
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
            @if (session('category_create'))
                new PNotify({
                    title: 'Success!',
                    text: '{{ session('category_create') }}',
                    type: 'success',
                    styling: 'bootstrap3'
                });
            @endif
            @if (session('category_delete'))
                new PNotify({
                    title: 'Success!',
                    text: '{{ session('category_delete') }}',
                    type: 'success',
                    styling: 'bootstrap3'
                });
            @endif
            @if ($errors->has('category_error'))
                new PNotify({
                    title: 'Oh No!',
                    text: '{{ $errors->first('category_error') }}',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            @endif
        });

        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this category?! ",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, redirect to the specified delete URL
                    window.location.href = deleteUrl;
                }
            });
        }
    </script>
    @include('layouts.backend.partial.footer_html_end')
@endsection
