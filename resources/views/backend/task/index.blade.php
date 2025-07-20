@extends('layouts.app')


@push('styles')
    <style>
        .card {
            position: relative;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .position-btn {
            position: absolute;
            top: 5%;
            right: 2%;
            z-index: 10 !important;
        }

        .card:hover {
            background: #22974552;
            transform: translateY(-10px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
        }

        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Task</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('admin.task.completed') }}" class="btn btn-label-info btn-round me-2">Task Completed</a>
            <x-model.m-button label='Add Task' target="categoryModel" />
        </div>
    </div>

    <x-model.dialog title="Add Task" route="{{ route('admin.task.store') }}" target="categoryModel" btnLabel="Add Task">

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
                        'prefix' => '',
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

        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="due_date" name="due_date" required>
            @error('due_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror"
                id="title" name="title" required>
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


    <x-model.dialog title="Progress" route="{{ route('admin.category.store') }}" target="model" btnLabel="Task Progress">

        <!-- Hidden User ID -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <input type="hidden" id="data_id" name="id" value="">


        {{-- <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                required>
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div> --}}

        <div class="mb-3">
            <label for="progressRange" class="form-label">Progress: <span id="progressValue">0%</span></label>
            <input type="range" class="form-range" min="0" max="100" step="1" id="progressRange"
                name="progress" value="50">
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

    <div class="mb-4">
        <span class="fs-2 py-2 px-3 rounded text-white shadow fw-bold bg-primary">Weekly Tasks Progress</span>
    </div>

    @foreach ($weeklyTasksProgress as $category)
        <div class="mb-4">
            <div class="d-flex gap-3 mb-2">
                <img src="{{ asset('storage/category/' . $category->picture) }}" class="img-thumbnail"
                    style='height:50px; width:50px'>
                <h4 class="fw-bold text-primary border-bottom pb-2 mb-3">
                    {{ $category->title }}
                </h4>
            </div>
  {{-- Overall Progress Bar --}}
    <div class="progress mb-3" style="height: 8px;">
      <div class="progress-bar bg-primary"
           role="progressbar"
           style="width: {{ $category->overall_progress }}%;"
           aria-valuenow="{{ $category->overall_progress }}"
           aria-valuemin="0"
           aria-valuemax="100">
      </div>
    </div>
    <small class="d-block mb-4">{{ $category->overall_progress }}%</small>


            <div class="row g-1">
                @foreach ($category->task as $task)
                    <div class="col-md-4">
                        <div
                            class="card  mb-3 border-start border-4
                            @if ($task->progress == 100) border-success
                            @elseif($task->progress >= 50) border-warning
                            @else border-danger @endif">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title mb-1 fw-semibold">{{ $task->title }}</h6>
                                    <div class="d-flex gap-2">
                                        <button data-bs-target="#model" data-bs-toggle="modal"
                                            data-id="{{ $task->id }}" class="btn-progress btn btn-success"><i
                                                class="fas fa-arrows-alt-h"></i></button>
                                        <a href="#" class="delete-btn btn btn-danger"><i
                                                class="fas fa-trash-alt"></i></a>
                                        <form id="delete-form" action="{{ route('admin.task.delete', $task->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                </div>

                                <p class="card-text small my-2 text-muted">{{ $task->description }}</p>

                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar data-progress
                                        @if ($task->progress == 100) bg-success
                                        @elseif($task->progress >= 60) bg-info
                                        @elseif($task->progress >= 50) bg-warning
                                        @elseif($task->progress <= 20) bg-danger
                                        @else bg-danger @endif"
                                        data-progress="{{ $task->progress }}" role="progressbar"
                                        style="width: {{ $task->progress }}%" aria-valuenow="{{ $task->progress }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Status: {{ ucfirst($task->status) }}</span>
                                    <span>Progress: {{ $task->progress }}%</span>
                                    <span>Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                </div>


                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        // from

        // $('#due_date').val(new Date().toISOString().split('T')[0])

        $('#preview-thumb').on('click', function() {
            $('.input-picture').click()
        })
        $('.input-picture').on('change', function(e) {
            var filePath = URL.createObjectURL(e.target.files[0]);
            $('#preview-thumb').show('300');
            $('#preview-thumb').attr('src', filePath); // Pass filePath as the src
        });


        $('.delete-btn').on('click', function(e) {
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


        // form





        function progressChange() {
            $('#progressValue').text(`${$('.form-range').val()}%`)
        }

        $(".form-range").on('input', progressChange);

        $('.btn-progress').on('click', function() {
            $('#data_id').val($(this).data('id'));
            //    $('.modal-title').text($(this).closest('.card-title').text());
            $('.form-range').val($('.data-progress').data('progress'))
            progressChange()

        })
    </script>
@endpush
