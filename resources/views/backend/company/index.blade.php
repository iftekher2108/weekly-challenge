@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Companies</h4>
                        @if (Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.company.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Company
                            </a>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="row">
                            @forelse($companies as $company)
                                <div class="col-md-4 mb-4">
                                    <div class="card border border-primary h-100 company-card" style="cursor:pointer;"
                                        onclick="window.location='{{ route('admin.company.show', $company) }}'">
                                        <div class="card-body d-flex flex-column align-items-center text-center">
                                            @if ($company->logo)
                                                <img src="{{ asset('storage/' . $company->logo) }}"
                                                    alt="{{ $company->name }}" class="rounded-circle mb-3" width="60"
                                                    height="60">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-building text-white"></i>
                                                </div>
                                            @endif
                                            <h5 class="card-title mb-1">{{ $company->name }}</h5>
                                            <p class="mb-1"><i class="fas fa-envelope"></i> {{ $company->email ?? 'N/A' }}
                                            </p>
                                            <p class="mb-1"><i class="fas fa-phone"></i> {{ $company->phone ?? 'N/A' }}
                                            </p>
                                            <div class="row">
                                                <span class="badge bg-info mb-1">Users: {{ $company->users_count }}</span>
                                                <span
                                                    class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-danger' }} mb-2">{{ ucfirst($company->status) }}</span>
                                            </div>

                                            <div class="d-flex gap-2 mt-2" role="group">
                                                @if (Auth::user()->canManageCompany($company->id))
                                                    <a href="{{ route('admin.company.edit', $company) }}"
                                                        class="btn btn-sm btn-warning" onclick="event.stopPropagation();"><i
                                                            class="fas fa-edit"></i></a>
                                                @endif
                                                @if (Auth::user()->isSuperAdmin())
                                                    <form action="{{ route('admin.company.delete', $company) }}"
                                                        method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                        onclick="deleteCompany(event)"
                                                        class="btn btn-sm delete-btn btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                @endif
                                                @if (Auth::user()->isSuperAdmin() || Auth::user()->canManageCompany($company->id))
                                                    <a href="{{ route('admin.report.company', $company->id) }}"
                                                        class="btn btn-sm btn-secondary"
                                                        onclick="event.stopPropagation();"><i class="fas fa-chart-bar"></i>
                                                        Report</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <div class="alert alert-info">No companies found.</div>
                                </div>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $companies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $('.delete-btn').click(function(e){
            e.stopPropagation();
            swal({
                title: "Are you sure you want to delete this company?",
                text: "You won't be able to revert this!",
                type: "warning",
                buttons: {
                    confirm: {
                        text: "Yes, delete it!",
                        className: "btn btn-success",
                    },
                    cancel: {
                        visible: true,
                        className: "btn btn-danger",
                    },
                },
            }).then((Delete) => {
                if (Delete) {
                    $(this).closest('.delete-form').submit();
                    swal({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        type: "success",
                        buttons: {
                            confirm: {
                                className: "btn btn-success",
                            },
                        },
                    });
                } else {
                    swal.close();
                }
            });
        });
    </script>
@endpush
