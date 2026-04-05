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
                <input type="hidden" name="id" id="student_id">
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
    <div class="card mb-3">
        <div class="card-body">

            <form id="importForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <input type="file" name="file" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success">Upload Excel</button>
                    </div>
                </div>
            </form>

            <div id="importErrors" class="mt-2"></div>

        </div>
    </div>
    <!-- DataTable -->
    <table class="table table-bordered" id="studentsTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Programme</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<script>
$(document).ready(function () {

    // CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DataTable INIT (IMPORTANT: assign to variable)
    let table = $('#studentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: "{{ route('students.list') }}",

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'students.name' },
            { data: 'email', name: 'students.email' },
            { data: 'department', name: 'department.name' },
            { data: 'programme', name: 'programme.name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],

        order: [[1, 'asc']]
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

        let id = $('#student_id').val();
        let url = id ? '/students/update/' + id : '/students/store';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),

            success: function (res) {

                $('#loader').hide();

                $('#msg').html(`<div class="alert alert-success">${res.message}</div>`);

                $('#studentForm')[0].reset();
                $('#student_id').val('');

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

    $(document).on('click', '.editBtn', function () {

        let id = $(this).data('id');

        $.get('/students/' + id + '/edit', function (data) {

            $('#student_id').val(data.id);
            $('input[name="name"]').val(data.name);
            $('input[name="email"]').val(data.email);
            $('#department').val(data.department_id).trigger('change');

            // wait for programme load
            setTimeout(() => {
                $('#programme').val(data.programme_id);
            }, 500);
        });
    });

    $(document).on('click', '.deleteBtn', function () {

        let id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this student?')) return;

        $.ajax({
            url: '/students/delete/' + id,
            type: 'DELETE',

            success: function (res) {
                $('#msg').html(`<div class="alert alert-success">${res.message}</div>`);
                table.ajax.reload();
            },

            error: function () {
                $('#msg').html('<div class="alert alert-danger">Delete failed</div>');
            }
        });
    });

    $('#importForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $('#importErrors').html('');

        $.ajax({
            url: "{{ route('students.import') }}",
            type: "POST",
            data: formData,
            processData: false, // IMPORTANT
            contentType: false, // IMPORTANT

            success: function(response) {
                if (response.status) {
                    alert(response.message);
                    $('#studentsTable').DataTable().ajax.reload();
                } else {
                    let errorHtml = '';
                    response.errors.forEach(function(err) {
                        errorHtml += `<p>${err}</p>`;
                    });
                    $('#importErrors').html(errorHtml);
                }
            },

            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '';

                $.each(errors, function(key, value) {
                    errorHtml += `<p>${value}</p>`;
                });

                $('#importErrors').html(errorHtml);
            }
        });
    });

});
</script>
@endsection
