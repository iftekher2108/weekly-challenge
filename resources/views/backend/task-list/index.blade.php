@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Task List</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            {{-- <a href="#" class="btn btn-label-info btn-round me-2">Refresh</a> --}}
            <x-model.m-button label='Add Task List' target="categoryModel" />
        </div>
    </div>


    <x-model.dialog title="Add Task List" route="admin.taskList.store" target="categoryModel" btnLabel="Add Task List">

        {{-- <!-- Hidden User ID -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}"> --}}

        <!-- Parent Category Select -->
        {{-- <div class="mb-3">

            <label for="parent_id" class="form-label">Parent Category</label>
            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                <option value="">-- None --</option>
                @foreach ($categories->whereNull('parent_id') as $parent)
                    @include('backend.catagory.select-option', ['category' => $parent, 'prefix' => ''])
                @endforeach
            </select>
            @error('parent_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div> --}}

        <!-- Picture Upload with Thumbnail -->
        <div class="mb-3">
            <label for="picture" class="form-label">Category Picture</label>
            <input type="file" class="form-control @error('picture') is-invalid @enderror" id="picture" name="picture"
                accept="image/*">
            <img id="preview-thumb" src="#" class="img-thumbnail mt-2" style="display: none; max-height: 100px;"
                alt="Preview">
            @error('picture')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                required>
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label @error('description') is-invalid @enderror">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

    </x-model.dialog>


    <div class="col-md-12">
        <div class="card">
            {{-- <div class="card-header">
                <h4 class="card-title">Multi Filter Select</h4>
            </div> --}}
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

                            @forelse($taskLists as $category)
                                <tr>
                                    <td><img src="{{ asset('storage/task-list/' . $category->picture) }}" alt="">
                                    </td>
                                    {{-- <td>{{ $category->parent->title ?? 'N/a' }}</td> --}}
                                    <td>{{ $category->title }}</td>
                                    {{-- <td>{{ $category->description }}</td> --}}
                                    <td>
                                        <a href="" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                                        <a href="" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
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
@endsection

{{--
@push('scripts')
    <script>
        $(".multi-filter-select").DataTable({
            stateSave: true,
            lengthMenu: [5, 10, 25, 50, 150, 200],
            pageLength: 5,
            columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }, // -1 = last column
            ],
        });
    </script>
@endpush --}}












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
