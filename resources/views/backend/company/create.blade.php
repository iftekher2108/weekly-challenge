@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create New Company</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.company.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Company Information</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Company Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="row g-1">

                                        <div class="col mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                                id="logo" name="logo" accept="image/*">
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">formats: JPEG, PNG, JPG . Max
                                                size: 2MB</small>
                                        </div>

                                        <div class="col mb-3">
                                            @php
                                                $options = ['active','inactive'];
                                            @endphp
                                            <label for="status" class="form-label">Company Status</label>

                                            <select name="status" class="p-2 form-select @error('status') is-invalid @enderror" id="status">
                                                @foreach ( $options as $option )
                                                <option value="{{ $option }}">{{ Str::of($option)->ucfirst() }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <h5>Company Admin Information</h5>

                                    <div class="mb-3">
                                        <label for="admin_user_ids" class="form-label">Select Company Admin(s) </label>
                                        <select
                                            class="form-control chosen-select @error('admin_user_ids') is-invalid @enderror"
                                            id="admin_user_ids" name="admin_user_ids[]" multiple >
                                            @foreach ($adminUsers as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ collect(old('admin_user_ids'))->contains($user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('admin_user_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Select multiple admins. Can't find the user?
                                            <br>
                                            <a href="{{ route('admin.user.create') }}" target="_blank">Create a new
                                                user</a></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.company.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Companies
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Create Company
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
@endsection
