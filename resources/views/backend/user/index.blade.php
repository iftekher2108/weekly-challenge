@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            @foreach ($companies as $company)
                <div class="col-md-3 mb-3">
                    <div class="card h-100 company-card {{ isset($selectedCompanyId) && $selectedCompanyId == $company->id ? 'border-primary' : '' }}"
                        style="cursor:pointer; {{ isset($selectedCompanyId) && $selectedCompanyId == $company->id ? 'box-shadow: 0 0 0 2px #007bff;' : '' }}"
                        onclick="window.location='{{ route('admin.user.index', ['company_id' => $company->id]) }}'">
                        <div class="card-body d-flex flex-column align-items-center text-center">
                            @if ($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}"
                                    class="rounded-circle mb-2" width="50" height="50">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                            @endif
                            <h6 class="mb-2">{{ $company->name }}</h6>
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge bg-info">Users: {{ $company->users()->count() }}</span>
                                <span
                                    class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($company->status) }}</span>
                            </div>
                            <span><strong>Created:</strong> {{ $company->created_at->format('d-m-Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($selectedCompanyId)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Users Management</h4>
                            <a href="{{ route('admin.user.create', ['company_id' => $selectedCompanyId]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add User
                            </a>
                        </div>
                        <div class="card-body">

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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>
                                                    @if ($user->picture)
                                                        <img src="{{ asset('storage/' . $user->picture) }}"
                                                            alt="{{ $user->name }}" class="rounded-circle" width="40"
                                                            height="40">
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
                                                    @foreach ($user->companies as $c)
                                                        @if ($c->id == $selectedCompanyId)
                                                            <span class="badge bg-info">{{ $c->name }}</span>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @switch($user->role)
                                                        @case('super-admin')
                                                            {{-- Do not show super-admins --}}
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
                                                    <div class="d-flex gap-2" role="group">
                                                        <a href="{{ route('admin.user.show', $user) }}"
                                                            class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        @if (Auth::user()->canManageCompany($selectedCompanyId))
                                                            <a href="{{ route('admin.user.edit', $user) }}"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-edit"></i></a>
                                                        @endif
                                                        @if (Auth::user()->canManageCompany($selectedCompanyId) && !$user->isSuperAdmin() && $user->id !== Auth::id())
                                                            <form action="{{ route('admin.user.delete', $user) }}"
                                                                method="POST" id='delete-form' class="d-inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm delete-btn btn-danger"><i
                                                                        class="fas fa-trash"></i></button>
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
            @endif

            @if ($unassignedUsers->count())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Unassigned Users (No Company)</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="" class="mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-2"><input type="text" name="search_name" class="form-control"
                                                placeholder="Name" value="{{ request('search_name') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_user_name" class="form-control"
                                                placeholder="Username" value="{{ request('search_user_name') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_email" class="form-control"
                                                placeholder="Email" value="{{ request('search_email') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_mobile" class="form-control"
                                                placeholder="Mobile" value="{{ request('search_mobile') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_gender" class="form-control"
                                                placeholder="Gender" value="{{ request('search_gender') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_religion"
                                                class="form-control" placeholder="Religion"
                                                value="{{ request('search_religion') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_address"
                                                class="form-control" placeholder="Address"
                                                value="{{ request('search_address') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_city" class="form-control"
                                                placeholder="City" value="{{ request('search_city') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_division"
                                                class="form-control" placeholder="Division"
                                                value="{{ request('search_division') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_district"
                                                class="form-control" placeholder="District"
                                                value="{{ request('search_district') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_zipcode"
                                                class="form-control" placeholder="Zipcode"
                                                value="{{ request('search_zipcode') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_nid" class="form-control"
                                                placeholder="NID" value="{{ request('search_nid') }}"></div>
                                        <div class="col-md-2"><input type="text" name="search_bid" class="form-control"
                                                placeholder="BID" value="{{ request('search_bid') }}"></div>
                                        <div class="col-md-2 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary w-100">Search</button>
                                            <a href="{{ route('admin.user.index') }}"
                                                class="btn btn-secondary w-100">Reset</a>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>Religion</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>Division</th>
                                                <th>District</th>
                                                <th>Zipcode</th>
                                                <th>NID</th>
                                                <th>BID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($unassignedUsers as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->user_name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->profile->mobile ?? '' }}</td>
                                                    <td>{{ $user->profile->gender ?? '' }}</td>
                                                    <td>{{ $user->profile->religion ?? '' }}</td>
                                                    <td>{{ $user->profile->address ?? '' }}</td>
                                                    <td>{{ $user->profile->city ?? '' }}</td>
                                                    <td>{{ $user->profile->division ?? '' }}</td>
                                                    <td>{{ $user->profile->district ?? '' }}</td>
                                                    <td>{{ $user->profile->zipcode ?? '' }}</td>
                                                    <td>{{ $user->profile->nid ?? '' }}</td>
                                                    <td>{{ $user->profile->bid ?? '' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">No unassigned users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                    {{ $unassignedUsers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endsection
