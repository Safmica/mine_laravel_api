<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request, $course_id, $meeting_id)
    {
        $meeting = Meeting::with('course')->where('id', $meeting_id)->where('course_id', $course_id)->first();

        if (!$meeting || $meeting->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan melihat file pada meeting ini'], 403);
        }

        $files = File::where('meeting_id', $meeting_id)->get();
        return response()->json($files);
    }

    public function store(Request $request, $course_id, $meeting_id)
    {
        $meeting = Meeting::with('course')
            ->where('id', $meeting_id)
            ->where('course_id', $course_id)
            ->first();

        if (!$meeting || $meeting->course->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan mengunggah file ke meeting ini'], 403);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');

        $filePath = $file->store('files', 'public');

        $meetingFile = File::create([
            'meeting_id' => $meeting_id,
            'user_id' => $request->user()->id,
            'filename' => $file->getClientOriginalName(),
            'filepath' => $filePath,
        ]);

        return response()->json($meetingFile, 201);
    }

    public function show(Request $request, $course_id, $meeting_id, $id)
    {
        $meetingFile = File::with('meeting.course')
            ->where('id', $id)
            ->where('meeting_id', $meeting_id)
            ->first();

        if (
            !$meetingFile ||
            !$meetingFile->meeting ||
            !$meetingFile->meeting->course ||
            $meetingFile->meeting->course->id != $course_id ||
            $meetingFile->user_id != $request->user()->id
        ) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        return response()->json($meetingFile);
    }

    public function update(Request $request, $course_id, $meeting_id, $id)
    {
        $meetingFile = File::with('meeting.course')
            ->where('id', $id)
            ->where('meeting_id', $meeting_id)
            ->first();

        if (
            !$meetingFile ||
            !$meetingFile->meeting ||
            !$meetingFile->meeting->course ||
            $meetingFile->meeting->course->id != $course_id ||
            $meetingFile->user_id != $request->user()->id
        ) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $validated = $request->validate([
            'filename' => 'required|string|max:255',
        ]);

        $meetingFile->filename = $validated['filename'];
        $meetingFile->save();

        return response()->json(['message' => 'Nama file berhasil diperbarui', 'data' => $meetingFile]);
    }


    public function destroy(Request $request, $course_id, $meeting_id, $file_id)
    {
        $file = File::find($file_id);

        if (
            !$file ||
            $file->meeting_id != $meeting_id ||
            $file->meeting->course->id != $course_id ||
            $file->meeting->course->user_id !== $request->user()->id
        ) {
            return response()->json(['message' => 'Tidak diizinkan menghapus file ini'], 403);
        }

        if (Storage::disk('public')->exists($file->filepath)) {
            Storage::disk('public')->delete($file->filepath);
        }
        $file->delete();

        return response()->json(['message' => 'File berhasil dihapus']);
    }
}
