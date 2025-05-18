<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->user();
        $courses = Course::where('user_id', $request->user()->id)->get();
        return response()->json($courses);
    }

    public function show(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course || $course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Course tidak ditemukan atau bukan milik Anda'], 403);
        }

        return response()->json($course);
    }


    public function store(Request $request)
    {
        $request->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course || $course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return response()->json($course);
    }


    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course || $course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $course->delete();

        return response()->json(['message' => 'Course berhasil dihapus']);
    }
}
