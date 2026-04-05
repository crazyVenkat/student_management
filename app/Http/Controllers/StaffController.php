<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Department;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function index()
    {
         $departments = Department::select('id', 'name')->get();
        return view('staff.index', compact('departments'));
    }

    public function list(Request $request) {
        if ($request->ajax()) {

            $query = Staff::with(['department:id,name'])
                ->select('staff.*');

            return DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('department', function ($row) {
                    return optional($row->department)->name ?? '-';
                })

                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-success">Active</span>';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>
                    ';
                })

                ->filterColumn('department', function ($query, $keyword) {
                    $query->whereHas('department', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                ->orderColumn('department', function ($query, $order) {
                    $query->join('departments', 'departments.id', '=', 'staff.department_id')
                        ->orderBy('departments.name', $order);
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:staff,email',
            'department_id' => 'required'
        ]);

        Staff::create($request->all());

        return response()->json(['message' => 'Staff added successfully']);
    }

    public function edit($id)
    {
        return response()->json(Staff::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:staff,email,'.$id,
            'department_id' => 'required'
        ]);

        $staff->update($request->all());

        return response()->json(['message' => 'Staff updated successfully']);
    }

    public function destroy($id)
    {
        Staff::findOrFail($id)->delete();

        return response()->json(['message' => 'Staff deleted successfully']);
    }
}
