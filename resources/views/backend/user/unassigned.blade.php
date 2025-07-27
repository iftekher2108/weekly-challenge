@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Unassigned Users (No Company)</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="mb-3">
                        @if(isset($companyId) && $companyId)
                            <input type="hidden" name="company_id" value="{{ $companyId }}">
                        @endif
                        <div class="row g-2">
                            <div class="col-md-2"><input type="text" name="search_name" class="form-control" placeholder="Name" value="{{ request('search_name') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_user_name" class="form-control" placeholder="Username" value="{{ request('search_user_name') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_email" class="form-control" placeholder="Email" value="{{ request('search_email') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_mobile" class="form-control" placeholder="Mobile" value="{{ request('search_mobile') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_gender" class="form-control" placeholder="Gender" value="{{ request('search_gender') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_religion" class="form-control" placeholder="Religion" value="{{ request('search_religion') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_address" class="form-control" placeholder="Address" value="{{ request('search_address') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_city" class="form-control" placeholder="City" value="{{ request('search_city') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_division" class="form-control" placeholder="Division" value="{{ request('search_division') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_district" class="form-control" placeholder="District" value="{{ request('search_district') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_zipcode" class="form-control" placeholder="Zipcode" value="{{ request('search_zipcode') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_nid" class="form-control" placeholder="NID" value="{{ request('search_nid') }}"></div>
                            <div class="col-md-2"><input type="text" name="search_bid" class="form-control" placeholder="BID" value="{{ request('search_bid') }}"></div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                                <a href="{{ route('admin.user.unassigned', $companyId ? ['company_id' => $companyId] : []) }}" class="btn btn-secondary w-100">Reset</a>
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
                                    <tr><td colspan="13" class="text-center">No unassigned users found.</td></tr>
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
</div>
@endsection
