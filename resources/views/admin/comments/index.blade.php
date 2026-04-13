@extends('admin.layout')

@section('title', 'Comments')
@section('subtitle', 'Customer comments approval & management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-white">💬 Comments</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">
                Pending: <span class="font-semibold text-yellow-300">{{ $pendingCount }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.comments.create') }}"
               class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-300">
                ➕ Add Admin Comment
            </a>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.comments.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-300 {{ $status === '' ? 'text-white' : 'text-[var(--text-secondary)] hover:text-white' }}"
           style="background: {{ $status === '' ? 'rgba(139, 92, 246, 0.18)' : 'rgba(255,255,255,0.03)' }}; border-color: rgba(255,255,255,0.08);">
            All
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-300 {{ $status === 'pending' ? 'text-white' : 'text-[var(--text-secondary)] hover:text-white' }}"
           style="background: {{ $status === 'pending' ? 'rgba(245, 158, 11, 0.18)' : 'rgba(255,255,255,0.03)' }}; border-color: rgba(255,255,255,0.08);">
            Pending
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-300 {{ $status === 'approved' ? 'text-white' : 'text-[var(--text-secondary)] hover:text-white' }}"
           style="background: {{ $status === 'approved' ? 'rgba(16, 185, 129, 0.18)' : 'rgba(255,255,255,0.03)' }}; border-color: rgba(255,255,255,0.08);">
            Approved
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                <tr>
                    <th class="text-left text-xs font-bold text-white/80 px-5 py-4">Name</th>
                    <th class="text-left text-xs font-bold text-white/80 px-5 py-4">Message</th>
                    <th class="text-left text-xs font-bold text-white/80 px-5 py-4">Status</th>
                    <th class="text-left text-xs font-bold text-white/80 px-5 py-4">Submitted</th>
                    <th class="text-right text-xs font-bold text-white/80 px-5 py-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($comments as $comment)
                    <tr>
                        <td class="px-5 py-4 align-top">
                            <div class="text-sm font-semibold text-white">{{ $comment->name }}</div>
                            <div class="text-xs text-[var(--text-muted)]">
                                {{ $comment->email ?: '—' }}
                                @if($comment->is_admin_created)
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          style="background: rgba(139, 92, 246, 0.2); border: 1px solid rgba(139, 92, 246, 0.35); color: #c4b5fd;">
                                        Admin
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 align-top">
                            <div class="text-sm text-white/90 max-w-2xl">
                                {{ \Illuminate\Support\Str::limit($comment->message, 220) }}
                            </div>
                        </td>
                        <td class="px-5 py-4 align-top">
                            @if($comment->is_approved)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"
                                      style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25); color: #34d399;">
                                    <span class="w-2 h-2 rounded-full bg-[#34d399]"></span>
                                    Approved
                                </span>
                                <div class="text-[10px] text-[var(--text-muted)] mt-1">
                                    {{ optional($comment->approved_at)->format('M d, Y H:i') ?: '—' }}
                                </div>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"
                                      style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25); color: #fbbf24;">
                                    <span class="w-2 h-2 rounded-full bg-[#fbbf24]"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-top">
                            <div class="text-xs text-[var(--text-secondary)]">
                                {{ $comment->created_at->format('M d, Y H:i') }}
                            </div>
                        </td>
                        <td class="px-5 py-4 align-top text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($comment->is_approved)
                                    <form method="POST" action="{{ route('admin.comments.unapprove', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-300 hover:scale-105"
                                                style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25); color: #fbbf24;">
                                            Hide
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-300 hover:scale-105"
                                                style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25); color: #34d399;">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                                      onsubmit="return confirm('Futa comment hii?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-300 hover:scale-105"
                                            style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #f87171;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center">
                            <p class="text-sm text-[var(--text-secondary)]">No comments found.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $comments->links() }}
    </div>
</div>
@endsection

