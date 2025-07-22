@extends('layouts.app')


@push('styles')
    <style>
        .card {
            position: relative;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
        }

        /* .position-btn {
            position: absolute;
            top: 5%;
            right: 2%;
            z-index: 10 !important;
        } */

        .task-card:hover {
            background: rgba(131, 179, 255, 0.801);
            transform: translateY(-3px);

        }

        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Weekly Tasks</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            {{-- <a href="{{ route('admin.task.completed') }}" class="btn btn-label-info btn-round me-2">Task Completed</a> --}}
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


    <x-model.dialog title="Progress" method_type="PUT" route="#" target="model" btnLabel="Task Progress">


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
                name="progress" >
        </div>

        <!-- Description -->
        {{-- <div class="mb-3">
            <label for="description" class="form-label @error('description') is-invalid @enderror">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div> --}}



    </x-model.dialog>


    <div class="row g-1">
        @include('backend.task.recartion-cat', ['categories' => $weeklyTasks, 'prefix' => 'weekly-'])
    </div>


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
            $('.form-range').val($(this).data('progress'))
            progressChange()
            const baseRoute = $(this).data('url');
            $('#model-form').attr('action', baseRoute);
            //    $('.modal-title').text($(this).closest('.card-title').text());

        })
    </script>
@endpush
