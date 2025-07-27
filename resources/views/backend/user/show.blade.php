@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Profile</h4>
                </div>
                <div class="card-body text-center">
                    @if($user->picture)
                        <img src="{{ asset('storage/' . $user->picture) }}"
                             alt="{{ $user->name }}"
                             class="rounded-circle mb-3"
                             width="120" height="120">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-user text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">@{{ $user->user_name }}</p>

                    <div class="row text-start">
                        <div class="col-12">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Company:</strong>
                                @if($user->company)
                                    <span class="badge bg-info">{{ $user->company->name }}</span>
                                @else
                                    <span class="badge bg-warning">No Company</span>
                                @endif
                            </p>
                            <p><strong>Role:</strong>
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
                            </p>
                            <p><strong>Status:</strong>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Unverified</span>
                                @endif
                            </p>
                            <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                            @if($user->email_verified_at)
                                <p><strong>Verified:</strong> {{ $user->email_verified_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->canManageCompany($user->company_id))
                    <div class="mt-3">
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Account Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Username:</strong></td>
                                    <td>{{ $user->user_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $user->last_login_at ?? 'Never' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6>Company Information</h6>
                            @if($user->company)
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Company:</strong></td>
                                    <td>{{ $user->company->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Email:</strong></td>
                                    <td>{{ $user->company->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Phone:</strong></td>
                                    <td>{{ $user->company->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Status:</strong></td>
                                    <td>
                                        @if($user->company->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @else
                            <p class="text-muted">No company assigned.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Permissions & Access</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Can Manage Company
                                            <span class="badge bg-{{ $user->canManageCompany() ? 'success' : 'danger' }}">
                                                {{ $user->canManageCompany() ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Is Super Admin
                                            <span class="badge bg-{{ $user->isSuperAdmin() ? 'danger' : 'secondary' }}">
                                                {{ $user->isSuperAdmin() ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Is Company Admin
                                            <span class="badge bg-{{ $user->isCompanyAdmin() ? 'primary' : 'secondary' }}">
                                                {{ $user->isCompanyAdmin() ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
                @if(Auth::user()->canManageCompany($user->company_id) && !$user->isSuperAdmin() && $user->id !== Auth::id())
                <form action="{{ route('admin.user.delete', $user) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
