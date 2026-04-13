@extends('admin.layout')

@section('title', 'Add Comment')
@section('subtitle', 'Create a comment that can appear on the homepage footer')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <h1 class="text-2xl font-bold text-white mb-1">➕ Add Admin Comment</h1>
        <p class="text-sm text-[var(--text-secondary)] mb-6">Unaweza kui-approve moja kwa moja ili ionekane kwenye footer.</p>

        <form method="POST" action="{{ route('admin.comments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-white/30">
                @error('name')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Email (optional)</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-white/30">
                @error('email')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Message</label>
                <textarea name="message" rows="5" required
                          class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-white/30">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-white/90">
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', '1') ? 'checked' : '' }}>
                Approve immediately
            </label>

            <div class="flex items-center justify-end gap-2 pt-2">
                <a href="{{ route('admin.comments.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold border text-[var(--text-secondary)] hover:text-white transition-all duration-300"
                   style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                    Cancel
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold text-white">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

