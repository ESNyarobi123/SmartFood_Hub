<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteComment;
use Illuminate\Http\Request;

class SiteCommentController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $status = $request->string('status')->toString();

        $query = SiteComment::query()->latest();
        if ($status === 'approved') {
            $query->approved()->orderByDesc('approved_at');
        } elseif ($status === 'pending') {
            $query->pending();
        }

        $comments = $query->paginate(15)->withQueryString();
        $pendingCount = SiteComment::pending()->count();

        return view('admin.comments.index', compact('comments', 'pendingCount', 'status'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.comments.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:500'],
            'is_approved' => ['nullable', 'boolean'],
        ]);

        $isApproved = (bool) ($validated['is_approved'] ?? false);

        SiteComment::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'],
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
            'approved_by_user_id' => $isApproved ? (int) auth()->id() : null,
            'is_admin_created' => true,
        ]);

        return redirect()->route('admin.comments.index')->with('success', 'Comment imehifadhiwa.');
    }

    public function approve(SiteComment $siteComment): \Illuminate\Http\RedirectResponse
    {
        $siteComment->forceFill([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by_user_id' => (int) auth()->id(),
        ])->save();

        return back()->with('success', 'Comment ime-approve.');
    }

    public function unapprove(SiteComment $siteComment): \Illuminate\Http\RedirectResponse
    {
        $siteComment->forceFill([
            'is_approved' => false,
            'approved_at' => null,
            'approved_by_user_id' => null,
        ])->save();

        return back()->with('success', 'Comment imefichwa (unapproved).');
    }

    public function destroy(SiteComment $siteComment): \Illuminate\Http\RedirectResponse
    {
        $siteComment->delete();

        return back()->with('success', 'Comment imefutwa.');
    }
}
