@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Company Information</h4>
                </div>
                <div class="card-body text-center">
                    @if($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}"
                             alt="{{ $company->name }}"
                             class="rounded-circle mb-3"
                             width="120" height="120">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-building text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <h5>{{ $company->name }}</h5>
                    <p class="text-muted">{{ $company->description }}</p>

                    <div class="row text-start">
                        <div class="col-12">
                            <p><strong>Email:</strong> {{ $company->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $company->phone ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $company->address ?? 'N/A' }}</p>
                            <p><strong>Status:</strong>
                                @if($company->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                            <p><strong>Created:</strong> {{ $company->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if(Auth::user()->canManageCompany($company->id))
                    <div class="mt-3">
                        <a href="{{ route('admin.company.edit', $company) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Company
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Company Users</h4>
                    <a href="{{ route('admin.company.users', $company) }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> View All Users
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Company Admins ({{ $company->admins->count() }})</h6>
                            @forelse($company->admins as $admin)
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                                <div>
                                    <strong>{{ $admin->name }}</strong><br>
                                    <small class="text-muted">{{ $admin->email }}</small>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">No company admins found.</p>
                            @endforelse
                        </div>

                        <div class="col-md-6">
                            <h6>Employees ({{ $company->employees->count() }})</h6>
                            @forelse($company->employees->take(5) as $employee)
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <strong>{{ $employee->name }}</strong><br>
                                    <small class="text-muted">{{ $employee->email }} ({{ $employee->role }})</small>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">No employees found.</p>
                            @endforelse

                            @if($company->employees->count() > 5)
                            <p class="text-muted">... and {{ $company->employees->count() - 5 }} more</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.company.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Companies
                </a>
                @if(Auth::user()->isSuperAdmin())
                <form action="{{ route('admin.company.delete', $company) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this company? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Company
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
