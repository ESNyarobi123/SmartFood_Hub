<?php

namespace App\Http\Controllers;

use App\Models\SiteComment;
use Illuminate\Http\Request;

class PublicCommentController extends Controller
{
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Tafadhali andika jina.',
            'email.email' => 'Email si sahihi.',
            'message.required' => 'Tafadhali andika ujumbe wako.',
            'message.max' => 'Ujumbe usizidi herufi 500.',
        ]);

        SiteComment::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'],
            'is_approved' => false,
            'is_admin_created' => false,
        ]);

        return back()->with('success', 'Asante! Ujumbe wako umetumwa na unasubiri uthibitisho wa admin.');
    }
}
