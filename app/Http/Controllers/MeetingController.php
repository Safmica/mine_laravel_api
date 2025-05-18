<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Course;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course || $course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan melihat data course ini'], 403);
        }

        $meetings = Meeting::where('course_id', $course_id)->get();
        return response()->json($meetings);
    }

    public function show(Request $request, $course_id, $id)
    {
        $meeting = Meeting::where('id', $id)
                          ->where('course_id', $course_id)
                          ->with('course')
                          ->first();

        if (!$meeting || $meeting->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Meeting tidak ditemukan atau bukan milik Anda'], 403);
        }

        return response()->json($meeting);
    }

    public function store(Request $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course || $course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan menambahkan meeting pada course ini'], 403);
        }

        $validated = $request->validate([
            'meeting_name' => 'required|string|max:255',
            'topic' => 'nullable|string',
        ]);

        $meeting = Meeting::create([
            'course_id' => $course_id,
            'meeting_name' => $validated['meeting_name'],
            'topic' => $validated['topic'] ?? null,
        ]);

        return response()->json($meeting, 201);
    }

    public function update(Request $request, $course_id, $id)
    {
        $meeting = Meeting::where('id', $id)
                          ->where('course_id', $course_id)
                          ->with('course')
                          ->first();

        if (!$meeting || $meeting->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $validated = $request->validate([
            'meeting_name' => 'sometimes|required|string|max:255',
            'topic' => 'nullable|string',
        ]);

        $meeting->update($validated);

        return response()->json($meeting);
    }

    public function destroy(Request $request, $course_id, $id)
    {
        $meeting = Meeting::where('id', $id)
                          ->where('course_id', $course_id)
                          ->with('course')
                          ->first();

        if (!$meeting || $meeting->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $meeting->delete();

        return response()->json(['message' => 'Meeting berhasil dihapus']);
    }
}
