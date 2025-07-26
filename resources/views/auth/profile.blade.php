@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card overflow-hidden">
                <div class="card-header bg-primary text-white fs-3">Update Profile</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="dob" class="col-md-4 col-form-label text-md-end">Date of Birth</label>
                            <div class="col-md-6">
                                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob', auth()->user()->profile->dob ?? '') }}">
                                @error('dob')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="mobile" class="col-md-4 col-form-label text-md-end">Mobile</label>
                            <div class="col-md-6">
                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile', auth()->user()->profile->mobile ?? '') }}">
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-end">Gender</label>
                            <div class="col-md-6">
                                <input id="gender" type="text" class="form-control @error('gender') is-invalid @enderror" name="gender" value="{{ old('gender', auth()->user()->profile->gender ?? '') }}">
                                @error('gender')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="religion" class="col-md-4 col-form-label text-md-end">Religion</label>
                            <div class="col-md-6">
                                <input id="religion" type="text" class="form-control @error('religion') is-invalid @enderror" name="religion" value="{{ old('religion', auth()->user()->profile->religion ?? '') }}">
                                @error('religion')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="blood" class="col-md-4 col-form-label text-md-end">Blood Group</label>
                            <div class="col-md-6">
                                <input id="blood" type="text" class="form-control @error('blood') is-invalid @enderror" name="blood" value="{{ old('blood', auth()->user()->profile->blood ?? '') }}">
                                @error('blood')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>
                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', auth()->user()->profile->address ?? '') }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="city" class="col-md-4 col-form-label text-md-end">City</label>
                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', auth()->user()->profile->city ?? '') }}">
                                @error('city')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="division" class="col-md-4 col-form-label text-md-end">Division</label>
                            <div class="col-md-6">
                                <input id="division" type="text" class="form-control @error('division') is-invalid @enderror" name="division" value="{{ old('division', auth()->user()->profile->division ?? '') }}">
                                @error('division')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="district" class="col-md-4 col-form-label text-md-end">District</label>
                            <div class="col-md-6">
                                <input id="district" type="text" class="form-control @error('district') is-invalid @enderror" name="district" value="{{ old('district', auth()->user()->profile->district ?? '') }}">
                                @error('district')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="zipcode" class="col-md-4 col-form-label text-md-end">Zipcode</label>
                            <div class="col-md-6">
                                <input id="zipcode" type="text" class="form-control @error('zipcode') is-invalid @enderror" name="zipcode" value="{{ old('zipcode', auth()->user()->profile->zipcode ?? '') }}">
                                @error('zipcode')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nid" class="col-md-4 col-form-label text-md-end">NID</label>
                            <div class="col-md-6">
                                <input id="nid" type="text" class="form-control @error('nid') is-invalid @enderror" name="nid" value="{{ old('nid', auth()->user()->profile->nid ?? '') }}">
                                @error('nid')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bid" class="col-md-4 col-form-label text-md-end">BID</label>
                            <div class="col-md-6">
                                <input id="bid" type="text" class="form-control @error('bid') is-invalid @enderror" name="bid" value="{{ old('bid', auth()->user()->profile->bid ?? '') }}">
                                @error('bid')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="task_point" class="col-md-4 col-form-label text-md-end">Task Point</label>
                            <div class="col-md-6">
                                <input id="task_point" type="number" class="form-control" name="task_point" value="{{ auth()->user()->profile->task_point ?? 0 }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bal_point" class="col-md-4 col-form-label text-md-end">Balance Point</label>
                            <div class="col-md-6">
                                <input id="bal_point" type="number" class="form-control" name="bal_point" value="{{ auth()->user()->profile->bal_point ?? 150 }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
