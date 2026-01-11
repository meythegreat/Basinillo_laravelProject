<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

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
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}