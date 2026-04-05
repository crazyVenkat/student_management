@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Staff Management</h3>

    <div id="msg"></div>
    <div id="loader" style="display:none;">Processing...</div>

    <!-- Form -->
    <div class="card mb-3">
        <div class="card-body">

            <form id="staffForm">
                @csrf
                <input type="hidden" id="staff_id">

                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>

                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
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
                        <button class="btn btn-success w-100">Save</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered" id="staffTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

</div>
<script>
$(function () {

    // CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#staffTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('staff.list') }}",

        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', name: 'staff.name' },
            { data: 'email', name: 'staff.email' },
            { data: 'phone', name: 'staff.phone' },
            { data: 'department', name: 'department.name' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });

    // Save / Update
    $('#staffForm').submit(function(e){
        e.preventDefault();

        let id = $('#staff_id').val();
        let url = id ? '/staff/update/'+id : '/staff/store';

        $.post(url, $(this).serialize(), function(res){
            $('#msg').html(`<div class="alert alert-success">${res.message}</div>`);
            $('#staffForm')[0].reset();
            $('#staff_id').val('');
            table.ajax.reload();
        });
    });

    // Edit
    $(document).on('click','.editBtn',function(){

        let id = $(this).data('id');

        $.get('/staff/'+id+'/edit', function(data){

            $('#staff_id').val(data.id);
            $('input[name=name]').val(data.name);
            $('input[name=email]').val(data.email);
            $('input[name=phone]').val(data.phone);
            $('#department').val(data.department_id);
        });
    });

    // Delete
    $(document).on('click','.deleteBtn',function(){
        let id = $(this).data('id');

        if(confirm('Delete this staff?')){
            $.ajax({
                url:'/staff/delete/'+id,
                type:'DELETE',
                success:function(res){
                    $('#msg').html(`<div class="alert alert-success">${res.message}</div>`);
                    table.ajax.reload();
                }
            });
        }
    });

});
</script>

@endsection
