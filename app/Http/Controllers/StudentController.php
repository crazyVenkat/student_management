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

            $query = Student::with(['department:id,name', 'programme:id,name'])
                ->select('students.*');

            return DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('department', function ($row) {
                    return $row->department->name ?? '-';
                })

                ->addColumn('programme', function ($row) {
                    return $row->programme->name ?? '-';
                })

                ->filterColumn('department', function ($query, $keyword) {
                    $query->whereHas('department', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('programme', function ($query, $keyword) {
                    $query->whereHas('programme', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

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
