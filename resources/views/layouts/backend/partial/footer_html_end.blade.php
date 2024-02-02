<script>
    $(document).ready(function() {
        @if (session('success'))
            new PNotify({
                title: 'Success!',
                text: '{{ session('success') }}',
                type: 'success',
                styling: 'bootstrap3'
            });
        @endif
        @if ($errors->has('fail'))
            new PNotify({
                title: 'Oh No!',
                text: '{{ $errors->first('fail') }}',
                type: 'error',
                styling: 'bootstrap3'
            });
        @endif
        @if ($errors->has('id'))
            new PNotify({
                title: 'Oh No!',
                text: '{{ $errors->first('id') }}',
                type: 'error',
                styling: 'bootstrap3'
            });
        @endif
    });

    function confirmDelete(deleteId) {
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
                var form = document.getElementById(deleteId);
                form.submit();
            }
        });
    }
</script>


</html>
