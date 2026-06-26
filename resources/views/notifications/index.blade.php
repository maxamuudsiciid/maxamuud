@extends('layouts.app')
@section('title','Notifications')
@section('page-title','Notifications')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Notifications</h2>
        <p class="page-sub">System alerts and messages</p>
    </div>
    @if($notifications->where('is_read', false)->count() > 0)
    <form action="{{ route('notifications.markAllRead') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary">
            <i class="fas fa-check-double me-2"></i>Mark All Read
        </button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div><i class="fas fa-bell me-2 text-danger"></i>All Notifications ({{ $notifications->total() }})</div>
        @if($notifications->where('is_read',false)->count() > 0)
        <span class="badge bg-danger">{{ $notifications->where('is_read',false)->count() }} unread</span>
        @endif
    </div>
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <div class="d-flex align-items-start gap-3 p-4 border-bottom {{ !$notif->is_read ? '' : 'opacity-75' }}"
            style="{{ !$notif->is_read ? 'background:#fffbeb;border-left:3px solid #f59e0b;' : 'background:#fff;border-left:3px solid transparent;' }}">
            <div style="width:40px;height:40px;border-radius:50%;background:{{ !$notif->is_read ? '#fef3c7' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-bell" style="color:{{ !$notif->is_read ? '#d97706' : '#94a3b8' }};font-size:14px;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div style="font-weight:{{ !$notif->is_read ? '700' : '500' }};font-size:14px;color:#1e293b;">
                            {{ $notif->title }}
                            @if(!$notif->is_read)
                                <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">NEW</span>
                            @endif
                        </div>
                        <div style="font-size:13px;color:#64748b;margin-top:3px;">{{ $notif->message }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1 ms-3">
                        <span style="font-size:11.5px;color:#94a3b8;white-space:nowrap;">{{ $notif->created_at->diffForHumans() }}</span>
                        <div class="d-flex gap-1">
                            @if(!$notif->is_read)
                            <form action="{{ route('notifications.markRead', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm py-0 px-2" style="font-size:11px;background:#fef3c7;color:#92400e;border:none;" title="Mark Read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            @if(Auth::user()->role === 'admin')
                            <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" class="confirm-delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm py-0 px-2" style="font-size:11px;background:#fee2e2;color:#991b1b;border:none;" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state py-5">
            <i class="fas fa-bell"></i>
            <p>No notifications yet. You're all caught up!</p>
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div class="card-body border-top py-3">{{ $notifications->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
