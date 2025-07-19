@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Task</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.task') }}" class="btn btn-label-info btn-round me-2">Refresh</a>
            <x-model.m-button label='Add Task' target="categoryModel" />
        </div>
    </div>


    <x-model.dialog title="Add Task" route="admin.task.store" target="categoryModel" btnLabel="Add Task">

        <!-- Hidden User ID -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <!-- Parent Category Select -->
        <div class="mb-3">

            <label for="cat_id" class="form-label">Category</label>
            <select class="form-select @error('cat_id') is-invalid @enderror" id="cat_id" name="cat_id">
                <option value="">-- None --</option>
                @foreach ($categories->whereNull('parent_id') as $parent)
                    @include('backend.catagory.select-option', [
                        'category' => $parent,
                        'selected' => old('cat_id'),
                         'prefix' => ''
                         ])
                @endforeach
            </select>
            @error('cat_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Picture Upload with Thumbnail -->
        <div class="mb-3">
            <label for="picture" class="form-label">Picture</label>
            <input type="file" class="form-control input-picture @error('picture') is-invalid @enderror" id="picture"
                name="picture" accept="image/*">
            <img id="preview-thumb" src="{{ asset('assets/backend/img/preview.png') }}" class="img-thumbnail mt-2"
                style="max-height: 120px;" alt="Preview">
            @error('picture')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                required>
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label @error('description') is-invalid @enderror">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

    </x-model.dialog>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    <div class="d-md-block d-none">

                    </div>
                    <form action="{{ route('admin.task') }}" method="GET" class="d-flex gap-1">
                        <input type="text" class="form-control" name="search" placeholder="Search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>


                {{-- <h4 class="card-title">Multi Filter Select</h4> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="multi-filter-select display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Picture</th>
                                {{-- <th>Parent</th> --}}
                                <th>title</th>
                                <th>description</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/task/' . $task->picture) }}" class="img-thumbnail" style="max-height: 70px;" alt="">
                                    </td>
                                    {{-- <td>{{ $taskList->parent->title ?? 'N/a' }}</td> --}}
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.task.edit',$task->id) }}" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="delete-btn btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                                        <form id="delete-form" action="{{ route('admin.task.delete',$task->id) }}" method="POST" >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <td>No Data Found</td>
                            @endforelse

                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <th>Picture</th>
                                <th>Parent</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th colspan="2">Action</th>

                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div>
        {{ $tasks->links() }}
    </div>

@endsection


@push('scripts')
    <script>
        // $(".multi-filter-select").DataTable({
        //     stateSave: true,
        //     lengthMenu: [5, 10, 25, 50, 150, 200],
        //     pageLength: 5,
        //     columnDefs: [{
        //             orderable: false,
        //             targets: [-1]
        //         }, // -1 = last column
        //     ],
        // });

        $('#preview-thumb').on('click', function() {
            $('.input-picture').click()
        })
        $('.input-picture').on('change', function(e) {
            var filePath = URL.createObjectURL(e.target.files[0]);
            $('#preview-thumb').show('300');
            $('#preview-thumb').attr('src', filePath); // Pass filePath as the src
        });


        $('.delete-btn').on('click',function(e) {
            swal({
              title: "Are you sure?",
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












{{-- <!-- Picture Upload with Preview -->
  <div class="mb-3">
    <label for="picture" class="form-label">Picture</label>
    <input type="file" class="form-control" id="picture" name="picture" onchange="previewThumb(event)">
    <div class="mt-2">
      <img id="thumbPreview" src="#" alt="Thumbnail Preview" class="img-thumbnail d-none" style="max-width: 200px;">
    </div>
  </div>

  <!-- Title -->
  <div class="mb-3">
    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="title" name="title" required>
  </div>

  <!-- Description -->
  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
  </div> --}}
