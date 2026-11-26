<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $topics = Topic::where('user_id', $request->user()->id)
            ->withCount('bookmarks')
            ->latest()
            ->get();

        return response()->json(['data' => $topics]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $topic = Topic::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Topik ditambahkan', 'data' => $topic], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $topic = Topic::where('user_id', $request->user()->id)->findOrFail($id);
        $topic->update(['name' => $request->name]);

        return response()->json(['message' => 'Topik diperbarui', 'data' => $topic]);
    }

    public function destroy(Request $request, $id)
    {
        $topic = Topic::where('user_id', $request->user()->id)->findOrFail($id);
        $topic->delete();

        return response()->json(['message' => 'Topik dihapus']);
    }
}