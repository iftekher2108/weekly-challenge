    @props([
        'title' => null,
        'route' => 'admin.dashboard',
        'target' => "model",
        'btnLabel' => 'Submit'

    ])

    <!-- Modal -->
    <div class="modal fade" id="{{ $target }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="model-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h1 class="modal-title fs-5" id="model-title text-white">{{ $title }}</h1>
                        <button type="reset" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ $slot }}
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">{{ $btnLabel }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
