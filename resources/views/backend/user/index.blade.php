@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Users Management</h4>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        @if($user->picture)
                                            <img src="{{ asset('storage/' . $user->picture) }}"
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle"
                                                 width="40" height="40">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->user_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->company)
                                            <span class="badge bg-info">{{ $user->company->name }}</span>
                                        @else
                                            <span class="badge bg-warning">No Company</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($user->role)
                                            @case('super-admin')
                                                <span class="badge bg-danger">Super Admin</span>
                                                @break
                                            @case('company-admin')
                                                <span class="badge bg-primary">Company Admin</span>
                                                @break
                                            @case('admin')
                                                <span class="badge bg-warning">Admin</span>
                                                @break
                                            @case('editor')
                                                <span class="badge bg-info">Editor</span>
                                                @break
                                            @case('creator')
                                                <span class="badge bg-success">Creator</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.user.show', $user) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->canManageCompany($user->company_id))
                                            <a href="{{ route('admin.user.edit', $user) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->canManageCompany($user->company_id) && !$user->isSuperAdmin() && $user->id !== Auth::id())
                                            <form action="{{ route('admin.user.delete', $user) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
