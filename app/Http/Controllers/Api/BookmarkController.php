<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Topic;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = Bookmark::with('topic')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $bookmarks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'key_points' => 'required|string',
        ]);

        // Pastikan topic_id milik user yang login
        Topic::where('user_id', $request->user()->id)->findOrFail($request->topic_id);

        $bookmark = Bookmark::create([
            'user_id' => $request->user()->id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'summary' => $request->summary,
            'key_points' => $request->key_points,
        ]);

        return response()->json([
            'message' => 'Bookmark disimpan',
            'data' => $bookmark->load('topic'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'key_points' => 'required|string',
        ]);

        Topic::where('user_id', $request->user()->id)->findOrFail($request->topic_id);
        $bookmark = Bookmark::where('user_id', $request->user()->id)->findOrFail($id);

        $bookmark->update([
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'summary' => $request->summary,
            'key_points' => $request->key_points,
        ]);

        return response()->json([
            'message' => 'Bookmark diperbarui',
            'data' => $bookmark->load('topic'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $bookmark = Bookmark::where('user_id', $request->user()->id)->findOrFail($id);
        $bookmark->delete();

        return response()->json(['message' => 'Bookmark dihapus']);
    }
}