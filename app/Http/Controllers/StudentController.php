<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Programme;
use App\Http\Requests\StoreStudentRequest;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function index() {
        $departments = Department::select('id', 'name')->get();
        return view('students.index', compact('departments'));
    }

    public function list(Request $request) {
        if ($request->ajax()) {

            $query = Student::with([
                    'department:id,name',
                    'programme:id,name'
                ])
                ->select('students.*');

            return DataTables::eloquent($query)
                ->addIndexColumn()

                // Department Column
                ->addColumn('department', function ($row) {
                    return optional($row->department)->name ?? '-';
                })

                // Programme Column
                ->addColumn('programme', function ($row) {
                    return optional($row->programme)->name ?? '-';
                })

                // Status Column (Optional but good for interview)
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                // Action Column (IMPORTANT)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>
                    ';
                })

                // Search for Department
                ->filterColumn('department', function ($query, $keyword) {
                    $query->whereHas('department', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                // Search for Programme
                ->filterColumn('programme', function ($query, $keyword) {
                    $query->whereHas('programme', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                // Sorting for Department
                ->orderColumn('department', function ($query, $order) {
                    $query->join('departments', 'departments.id', '=', 'students.department_id')
                        ->orderBy('departments.name', $order);
                })

                // Sorting for Programme
                ->orderColumn('programme', function ($query, $order) {
                    $query->join('programmes', 'programmes.id', '=', 'students.programme_id')
                        ->orderBy('programmes.name', $order);
                })

                // Allow HTML rendering
                ->rawColumns(['action', 'status'])

                ->make(true);
        }
    }

    public function store(StoreStudentRequest $request) {

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'programme_id' => $request->programme_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Student created successfully'
        ]);
    }

    public function getProgrammes($department_id) {
        return Programme::where('department_id', $department_id)
            ->select('id', 'name')
            ->get();
    }
}
