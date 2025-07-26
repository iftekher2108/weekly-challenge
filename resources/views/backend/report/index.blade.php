@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold mb-3">Reports</h3>
        <form method="GET" action="{{ route('admin.report') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label for="period" class="form-label">Period</label>
                    <select name="period" id="period" class="py-2 form-select">
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="range" {{ $period == 'range' ? 'selected' : '' }}>Date Range</option>
                    </select>
                </div>
                <div class="col-auto" id="date-range-fields" style="display: {{ $period == 'range' ? 'block' : 'none' }};">
                    <label for="start" class="form-label">Start Date</label>
                    <input type="date" name="start" id="start" class="form-control" value="{{ $start }}">
                </div>
                <div class="col-auto" id="date-range-fields-end"
                    style="display: {{ $period == 'range' ? 'block' : 'none' }};">
                    <label for="end" class="form-label">End Date</label>
                    <input type="date" name="end" id="end" class="form-control" value="{{ $end }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary py-2">Filter</button>
                </div>
            </div>
        </form>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Created</p>
                                    <h4 class="card-title">{{ $tasksCreated }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Completed</p>
                                    <h4 class="card-title">{{ $tasksCompleted }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-spinner"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total In Progress</p>
                                    <h4 class="card-title">{{ $tasksInProgress }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Not Completed</p>
                                    <h4 class="card-title">{{ $tasksNotCompleted }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-2">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Doughnut Chart</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="doughnutChart" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row mt-4 g-3">

            <div class="col-md-4">
                <h5>Completed Tasks ({{ $completedTasks->count() }})</h5>
                <ul class="list-group mb-4">
                    @forelse($completedTasks as $task)
                        <li class="list-group-item">{{ $task->title }}<span
                                class="badge
                        @if ($task->status == 'completed') bg-success
                        @elseif ($task->status == 'progress')
                            bg-warning
                        @elseif ($task->status == 'not_completed')
                            bg-danger @endif">{{ ucfirst($task->status) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No completed tasks.</li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-4">
                <h5>In Progress Tasks ({{ $inProgressTasks->count() }})</h5>
                <ul class="list-group mb-4">
                    @forelse($inProgressTasks as $task)
                        <li class="list-group-item">{{ $task->title }}<span
                                class="badge
                        @if ($task->status == 'completed') bg-success
                        @elseif ($task->status == 'progress')
                            bg-warning
                        @elseif ($task->status == 'not_completed')
                            bg-danger @endif">{{ ucfirst($task->status) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No in-progress tasks.</li>
                    @endforelse
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Not Completed Tasks ({{ $notCompletedTasks->count() }})</h5>
                <ul class="list-group mb-4">
                    @forelse($notCompletedTasks as $task)
                        <li class="list-group-item">{{ $task->title }}<span
                                class="badge
                        @if ($task->status == 'completed') bg-success
                        @elseif ($task->status == 'progress')
                            bg-warning
                        @elseif ($task->status == 'not_completed')
                            bg-danger @endif">{{ ucfirst($task->status) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No not-completed tasks.</li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-12">
                <h5>Created Tasks ({{ $createdTasks->count() }})</h5>
                <ul class="list-group mb-4">
                    @forelse($createdTasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $task->title }}
                            <span
                                class="badge
                        @if ($task->status == 'completed') bg-success
                        @elseif ($task->status == 'progress')
                            bg-warning
                        @elseif ($task->status == 'not_completed')
                            bg-danger @endif">{{ ucfirst($task->status) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No tasks found.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('period').addEventListener('change', function() {
            var show = this.value === 'range';
            document.getElementById('date-range-fields').style.display = show ? 'block' : 'none';
            document.getElementById('date-range-fields-end').style.display = show ? 'block' : 'none';
        });

        //  DOUGHNUT CHART START
        var doughnutChart = document.getElementById("doughnutChart").getContext("2d");
        var myDoughnutChart = new Chart(doughnutChart, {
            type: "doughnut",
            data: {
                datasets: [{
                    data: [{{ $tasksCompleted }}, {{ $tasksInProgress }}, {{ $tasksNotCompleted }}],
                    backgroundColor: ["#1d7af3", "#fdaf4b", "#f3545d"],
                }, ],

                labels: ["Task Completed", "Task Progress", "Red"],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
        });
        //  DOUGHNUT CHART END
    </script>
@endpush
