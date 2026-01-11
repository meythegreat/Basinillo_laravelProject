<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class StudentController extends Controller
{
    /**
     * Display dashboard with students and courses
     */
    public function index(Request $request)
    {
        $query = Student::with('course');

         if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('course_filter') && $request->course_filter != '') {
            $query->where('course_id', $request->course_filter);
        }

        $students = $query->latest()->get();
        $courses = Course::all();
        $activeCourses = Course::count();

        return view('dashboard', compact('students', 'courses', 'activeCourses'));
    }

    /**
     * Store a new student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

         if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('student-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Student::create($validated);

        return redirect()->back()->with('success', 'Student added successfully.');
    }

    /**
     * Update an existing student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

         if ($request->hasFile('photo')) {
             if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $photoPath = $request->file('photo')->store('student-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $student->update($validated);

        return redirect()->back()->with('success', 'Student updated successfully.');
    }

    /**
     * Delete a student
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student successfully moved to trash.');
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->with('course')->latest('deleted_at')->get();
        $courses = Course::all();

        return view('trash', compact('students', 'courses'));
    }

    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('students.trash')->with('success', 'Student restored successfully.');
    }

    public function forceDelete($id)
    {
        $student = Student::withTrashed()->findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->forceDelete();

        return redirect()->route('students.trash')->with('success', 'Student permanently deleted.');
    }

    public function export(Request $request)
    {
        $query = Student::with('course');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('course_filter') && $request->course_filter != '') {
            $query->where('course_id', $request->course_filter);
        }

        $students = $query->latest()->get();

        $filename = 'students_export_' . date('Y-m-d_His') . '.pdf';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Students Export</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 30px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #333;
                    text-align: center;
                    margin-bottom: 10px;
                }
                .export-info {
                    text-align: center;
                    color: #666;
                    margin-bottom: 30px;
                    font-size: 14px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th {
                    background-color: #4472C4;
                    color: white;
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #2e5c9a;
                }
                td {
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #f0f0f0;
                }
                .footer {
                    margin-top: 20px;
                    padding: 15px;
                    background-color: #f0f0f0;
                    border-radius: 5px;
                    text-align: center;
                    font-weight: bold;
                    color: #333;
                }
                @media print {
                    body {
                        background-color: white;
                    }
                    .container {
                        box-shadow: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Students Export Report</h1>
                <div class="export-info">
                    Exported on: ' . date('F d, Y \a\t h:i A') . '<br>
                    Total Records: ' . $students->count() . '
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Course</th>
                            <th>Enrolled Date</th>
                        </tr>
                    </thead>
                    <tbody>';

                $number = 1;
                foreach ($students as $student) {
                    $html .= '<tr>
                    <td>' . $number++ . '</td>
                    <td>' . htmlspecialchars($student->name) . '</td>
                    <td>' . htmlspecialchars($student->email) . '</td>
                    <td>' . htmlspecialchars($student->phone) . '</td>
                    <td>' . htmlspecialchars($student->address) . '</td>
                    <td>' . htmlspecialchars($student->course ? $student->course->course_name : 'No Course') . '</td>
                    <td>' . $student->created_at->format('Y-m-d H:i:s') . '</td>
                </tr>';
                }

                $html .= '</tbody>
                </table>

                <div class="footer">
                    Total Students: ' . $students->count() . '
                </div>
            </div>
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}