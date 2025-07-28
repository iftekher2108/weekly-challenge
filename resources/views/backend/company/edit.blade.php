@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Company</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.company.update', $company->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Company Information</h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $company->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description', $company->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Company Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $company->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $company->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $company->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row g-2">

                                        <div class="col-md-6 mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                                id="logo" name="logo" accept="image/*">
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">formats: JPEG, PNG, JPG . Max
                                                size: 2MB</small>
                                            @if ($company->logo)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo"
                                                        height="60">
                                                </div>
                                            @endif
                                        </div>

                                        @if (Auth::user()->isSuperAdmin())
                                            <div class="col mb-3">
                                                @php
                                                    $options = ['active', 'inactive'];
                                                @endphp
                                                <label for="status" class="form-label">Company Status</label>

                                                <select name="status"
                                                    class="p-2 form-select @error('status') is-invalid @enderror"
                                                    id="status">
                                                    @foreach ($options as $option)
                                                        <option value="{{ $option }}">
                                                            {{ Str::of($option)->ucfirst() }}</option>
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif


                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h5>Company Admin Information</h5>
                                    <div class="mb-3">
                                        <label for="admin_user_ids" class="form-label">Select Company Admin(s) <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control chosen-select @error('admin_user_ids') is-invalid @enderror"
                                            id="admin_user_ids" name="admin_user_ids[]" multiple required>
                                            @foreach ($adminUsers as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ collect(old('admin_user_ids', $company->admins()->pluck('users.id')->toArray()))->contains($user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('admin_user_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Select multiple admins. Can't find the user? <a
                                                href="{{ route('admin.user.create') }}" target="_blank">Create a new
                                                user</a></small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex mt-4 justify-content-between">
                                    <a href="{{ route('admin.company.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Company</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
