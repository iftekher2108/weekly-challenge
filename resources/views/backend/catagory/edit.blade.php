@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Category Update</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>

    </div>

    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Hidden User ID -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Parent Category Select -->
                    <div class="mb-3">

                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id"
                            name="parent_id">
                            <option value="">-- None --</option>
                            @foreach ($categories->whereNull('parent_id') as $parent)
                                @include('backend.catagory.select-option', [
                                    'category' => $parent,
                                    'selected' => old('parent_id',$category->parent_id),
                                    'prefix' => '',
                                ])
                            @endforeach
                        </select>
                        @error('parent_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Picture Upload with Thumbnail -->
                    <div class="mb-3">
                        <label for="picture" class="form-label">Picture</label>
                        <input type="file" class="form-control input-picture @error('picture') is-invalid @enderror"
                            id="picture" name="picture" accept="image/*">
                        <img id="preview-thumb" src="{{ asset('assets/backend/img/preview.png') }}"
                            class="img-thumbnail mt-2" style="max-height: 120px;" alt="Preview">
                        @error('picture')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('title',$category->title) }}"
                            class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                            required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description"
                            class="form-label @error('description') is-invalid @enderror">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description',$category->description) }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
