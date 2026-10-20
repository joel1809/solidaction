<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::query()
            ->with('post')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('body', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.comments.index', [
            'comments' => $comments,
            'counts' => Comment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Comment::STATUSES))],
        ]);

        $comment->update($data);

        return back()->with('success', 'Le commentaire est désormais « '.$comment->status_label.' ».');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Le commentaire a été supprimé.');
    }
}
