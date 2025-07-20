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
            <a href="{{ route('admin.task') }}" class="btn btn-label-info btn-round me-2">Task Progress</a>

        </div>
    </div>

        <div class="mb-4">
        <span class="fs-2 py-2 px-3 rounded text-white shadow fw-bold bg-primary">Weekly Tasks Completed</span>
    </div>

    @foreach ($weeklyTasksCompleted as  $category)
        <div class="mb-4">
           <div class="d-flex gap-3 mb-2">
                <img src="{{ asset('storage/category/' . $category->picture) }}" class="img-thumbnail"
                    style='height:50px; width:50px'>
                <h4 class="fw-bold text-primary border-bottom pb-2 mb-3">
                    {{ $category->title }}
                </h4>
            </div>

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

                                </div>

                                <p class="card-text small text-muted">{{ $task->description }}</p>

                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar
                                        @if ($task->progress == 100) bg-success
                                        @elseif($task->progress >= 50) bg-warning
                                        @else bg-danger @endif"
                                        role="progressbar" style="width: {{ $task->progress }}%"
                                        aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100">
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


