@extends('layouts.app')
@section('title','User Management')
@section('page-title','User Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">User Management</h2>
        <p class="page-sub">Manage all system accounts</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-blood">
        <i class="fas fa-user-plus me-2"></i>Add User
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-users-gear me-2 text-danger"></i>All Users ({{ $users->total() }})</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $canViewImage = in_array(Auth::user()->role, ['admin', 'staff']) || Auth::id() === $user->id;
                                @endphp
                                @if($user->image && $canViewImage)
                                    <img src="{{ asset('storage/profiles/'.$user->image) }}" alt="Profile" style="width:32px;height:32px;border-radius:50%;object-fit:cover;cursor:pointer;flex-shrink:0;" onclick="window.open(this.src, '_blank')">
                                @else
                                    <div style="width:32px;height:32px;border-radius:50%;background:{{ $user->role==='admin'?'linear-gradient(135deg,#7c3aed,#4c1d95)':($user->role==='staff'?'linear-gradient(135deg,#2563eb,#1e40af)':($user->role==='donor'?'linear-gradient(135deg,#dc2626,#7f1d1d)':'linear-gradient(135deg,#16a34a,#166534)')) }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                @endif
                                <span style="font-weight:600;font-size:13.5px;">{{ $user->name }}</span>
                                @if($user->id === Auth::id())
                                    <span class="badge" style="background:#f3e8ff;color:#7c3aed;font-size:10px;">You</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php $roleColors = ['admin'=>'#f3e8ff,#7c3aed','staff'=>'#dbeafe,#1e40af','donor'=>'#fee2e2,#991b1b','hospital'=>'#dcfce7,#166534']; @endphp
                            @php $rc = explode(',', $roleColors[$user->role] ?? '#f1f5f9,#64748b'); @endphp
                            <span class="badge" style="background:{{ $rc[0] }};color:{{ $rc[1] }};font-size:11px;padding:4px 10px;border-radius:20px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="confirm-delete">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users-gear"></i>
                                <p>No users found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-body border-top py-3">{{ $users->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
