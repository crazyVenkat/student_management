<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Programme;
use App\Http\Requests\StoreStudentRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use App\Jobs\ImportStudentsJob;
use Illuminate\Support\Facades\Storage;

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
                ->rawColumns(['action'])

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

    // Edit (Fetch data)
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    // Update
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'department_id' => 'required',
            'programme_id' => 'required',
        ]);

        $student->update($request->all());

        return response()->json([
            'message' => 'Student updated successfully'
        ]);
    }

    // Delete
    public function destroy($id)
    {
        Student::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ]);
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Store file properly
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $filePath = $file->storeAs('imports', $fileName, 'public');

        // Dispatch job with FULL PATH
        ImportStudentsJob::dispatch($filePath);

        return response()->json([
            'status' => true,
            'message' => 'Import started in background'
        ]);
    }

}
