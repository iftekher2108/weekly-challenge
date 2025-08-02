@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users for Company: {{ $company->name }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role in Company</th>
                                    {{-- @if (Auth::user()->canManageCompany($company->id)) --}}
                                        <th>Action</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge {{ $user->pivot->role == 'admin' ? 'badge-primary' : 'badge-info' }} ">{{ $user->pivot->role }}</span></td>
                                        {{-- @if (Auth::user()->canManageCompany($company->id)) --}}
                                            <td>
                                                <div class="d-flex gap-2" role="group">
                                                    <a href="{{ route('admin.user.show',['id' => $user->id, "company_id" => $company->id]) }}"
                                                        class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                    @if (Auth::user()->canManageCompany($company->id))
                                                        <a href="{{ route('admin.user.edit', $user) }}"
                                                            class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                    @endif
                                                    @if (
    Auth::user()->canManageCompany($company->id) &&
    $user->id !== Auth::id() &&
    (
        Auth::user()->isSuperAdmin() || // SuperAdmin can delete anyone
        ($user->roleForCompany($company->id) !== 'admin')        // Admin can delete if target is NOT a SuperAdmin
    ))

                                                         <a class="btn btn-sm btn-danger"
                                                         href="{{ route('admin.user.remove-form-com',['id' => $user->id, 'company_id' => $company->id ]) }}"
                                                          >
                                                           <i class="fas fa-trash"></i>
                                                         </a>

                                                    {{-- <form action="{{ route('admin.user.delete', $user) }}"
                                                            method="POST" id='delete-form' class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm delete-btn btn-danger"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form> --}}
                                                    @endif
                                                </div>
                                            </td>
                                        {{-- @endif --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <a href="{{ route('admin.company.index') }}" class="btn btn-secondary">Back to Companies</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.delete-btn').on('click', function(e) {
            e.stopPropagation();
            swal({
                title: "Are you sure you want to remove this user?",
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
                    $('#delete-form').submit();
                    swal({
                        title: "Deleted!",
                        text: "Your user has been removed.",
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
