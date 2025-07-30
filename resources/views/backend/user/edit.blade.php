@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User: {{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h5>User Information</h5>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="user_name" class="form-label">Username *</label>
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                           id="user_name" name="user_name" value="{{ old('user_name', $user->user_name) }}" required
                                           {{ !Auth::user()->isSuperAdmin() && $user->id !== Auth::id() ? 'disabled' : '' }}
                                           >
                                    @error('user_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required
                                           {{ !Auth::user()->isSuperAdmin() && $user->id !== Auth::id() ? 'disabled' : '' }}
                                           >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Account Settings</h5>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave blank to keep current password. Minimum 8 characters if changed.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company Roles *</label>
                                    <div class="row">
                                        @foreach($companies as $company)
                                            <div class="col-md-6 mb-2">
                                                <div class="card p-2">
                                                    <div class="mb-1"><strong>{{ $company->name }}</strong></div>
                                                    <select class="form-select @error('company_roles.' . $company->id) is-invalid @enderror"
                                                            name="company_roles[{{ $company->id }}]">
                                                        @foreach($roles as $roleValue => $roleLabel)
                                                            <option value="{{ $roleValue }}"
                                                                @if(isset($user->companies) && $user->companies->contains('id', $company->id) && $user->companies->where('id', $company->id)->first()->pivot->role == $roleValue)
                                                                    selected
                                                                @endif
                                                                @if($user->id === Auth::id() && $roleValue !== ($user->companies->where('id', $company->id)->first()->pivot->role ?? null))
                                                                    disabled
                                                                @endif
                                                            >
                                                                {{ $roleLabel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('company_roles.' . $company->id)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="form-text text-muted">
                                        Assign a role for each company. Select "No Access" to remove the user from that company.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Users
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Disable admin role option for non-super admins
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const isSuperAdmin = {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }};
    const isOwnAccount = {{ $user->id === Auth::id() ? 'true' : 'false' }};

    if (!isSuperAdmin && !isOwnAccount) {
        const adminOption = roleSelect.querySelector('option[value="admin"]');
        if (adminOption) {
            adminOption.disabled = true;
        }
    }
});
</script>
@endsection
