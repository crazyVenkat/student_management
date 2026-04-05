@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Students</h3>
    <div id="msg"></div>
    <div id="loader" style="display:none;">Processing...</div>
    <!-- Add Student Form -->
    <div class="card mb-4">
        <div class="card-header">Add Student</div>

        <div class="card-body">

            <form id="studentForm">
                @csrf

                <div class="row">

                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>

                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>

                    <div class="col-md-2">
                        <select name="department_id" id="department" class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="programme_id" id="programme" class="form-control">
                            <option value="">Select Programme</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Save</button>
                    </div>

                </div>
            </form>

            <div id="msg" class="mt-2"></div>

        </div>
    </div>

    <!-- DataTable -->
    <table class="table table-bordered" id="studentTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Programme</th>
            </tr>
        </thead>
    </table>
</div>
<script>
$(document).ready(function () {

    // DataTable
    var table = $('#studentTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/students/list') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'department', name: 'department' },
            { data: 'programme', name: 'programme' }
        ]
    });

    // Department change
    $('#department').change(function () {

        let id = $(this).val();

        $('#programme').html('<option>Loading...</option>');

        $.ajax({
            url: '/programmes/' + id,
            type: 'GET',
            success: function (data) {

                let options = '<option value="">Select Programme</option>';

                data.forEach(item => {
                    options += `<option value="${item.id}">${item.name}</option>`;
                });

                $('#programme').html(options);
            }
        });
    });

    // Submit form
    $('#studentForm').submit(function (e) {
        e.preventDefault();

        $('#loader').show();
        $('#msg').html('');

        $.ajax({
            url: '/students/store',
            type: 'POST',
            data: $(this).serialize(),

            success: function (res) {

                $('#loader').hide();

                $('#msg').html(`<div class="alert alert-success">${res.message}</div>`);

                $('#studentForm')[0].reset();

                table.ajax.reload();
            },

            error: function (xhr) {

                $('#loader').hide();

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '<div class="alert alert-danger">';

                    $.each(errors, function (key, value) {
                        errorMsg += value[0] + '<br>';
                    });

                    errorMsg += '</div>';

                    $('#msg').html(errorMsg);

                } else {
                    $('#msg').html('<div class="alert alert-danger">Something went wrong</div>');
                }
            }
        });
    });

});
</script>
@endsection
