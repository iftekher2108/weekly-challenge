   @foreach ($categories as $category)
       @if ($category->parent)
           <div class=" col-md-6">
       @endif
       <div class="@if ($category->parent) card @endif p-2 mb-3">

           <div class="d-flex gap-3 mb-1">
               <img src="{{ asset('storage/category/' . $category->picture) }}" class="img-thumbnail"
                   style='height:50px; width:50px'>
               <h4
                   class="fw-bold text-primary @if ($category->parent) border-primary @endif border-bottom pb-2">
                   {{ $category->title }}
               </h4>
           </div>

           @if ($category->parent)
               <h6 class="text-muted">Parent: {{ $category->parent->title }}</h6>
               {{-- Overall Progress Bar --}}
               <div class="progress mb-1" style="height: 8px;">
                   <div class="progress-bar
                        @if ($category->overall_progress == 100) bg-success
                        @elseif($category->overall_progress >= 65) bg-primary
                        @elseif($category->overall_progress > 45) bg-info
                        @elseif($category->overall_progress > 10) bg-warning
                        @elseif($category->overall_progress <= 10) bg-danger
                        @else bg-danger @endif"
                       role="progressbar" style="width: {{ $category->overall_progress }}%;"
                       aria-valuenow="{{ $category->overall_progress }}" aria-valuemin="0" aria-valuemax="100">
                   </div>
               </div>
               <small class="d-block mb-4">{{ $category->overall_progress }}%</small>
           @endif

           <div class="row g-1">
               @foreach ($category->task as $task)
                   <div class="col-md-12">
                       <div
                           class="card task-card mb-2 border-start border-4
                            @if ($task->progress == 100) border-success
                            @elseif($task->progress >= 65) border-primary
                            @elseif($task->progress > 45) border-info
                            @elseif($task->progress > 10) border-warning
                            @elseif($task->progress <= 10) border-danger
                            @else border-danger @endif">
                           <div class="card-body">
                               <div class="d-flex justify-content-between">
                                   <h6 class="card-title mb-1 fw-semibold">{{ $task->title }}</h6>

                                   @if ($task->status == 'completed')
                                       <span class="badge bg-success">Completed</span>
                                   @elseif ($task->status == 'progress')
                                       <div class="d-flex gap-2">
                                           <button data-bs-target="#model" data-bs-toggle="modal"
                                               data-progress="{{ $task->progress }}"
                                               data-url="{{ route('admin.task.progress', $task->id) }}"
                                               class="btn-progress btn btn-success"><i
                                                   class="fas fa-arrows-alt-h"></i></button>
                                           <a href="#" class="delete-btn btn btn-danger"><i
                                                   class="fas fa-trash-alt"></i></a>
                                           <form id="delete-form" action="{{ route('admin.task.delete', $task->id) }}"
                                               method="POST">
                                               @csrf
                                               @method('DELETE')
                                           </form>
                                       </div>
                                   @endif


                               </div>

                               <p class="card-text small my-2 text-muted">{{ $task->description }}</p>

                               <div class="progress mb-2" style="height: 10px;">
                                   <div class="progress-bar data-progress
                                        @if ($task->progress == 100) bg-success
                                        @elseif($task->progress >= 65) bg-primary
                                        @elseif($task->progress > 45) bg-info
                                        @elseif($task->progress > 10) bg-warning
                                        @elseif($task->progress <= 10) bg-danger
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

       @if ($category->parent)
           </div>
       @endif
   @endforeach
