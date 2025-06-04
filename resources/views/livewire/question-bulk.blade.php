<div>
    <!-- Upload Form -->
    <div class="card-body">
        <div class="row align-items-end ">
            <div class="col-md-6 col-sm-12">
                <a class="btn btn-primary" href="{{ route('download.sample.file.questions') }}"> Download
                    sample file</a>
            </div>

            <div class="col-md-6 col-sm-12">
                <!-- Left side: Bulk Upload Form -->
                <form wire:submit.prevent="uploadUsers">
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" wire:model="file" class="form-control">
                        @error('file')
                            <span class="error">{{ $message }}</span>
                        @enderror

                        <div class="text-end">
                            <div wire:loading wire:target="uploadUsers" class="spinner-border text-primary"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary" style="white-space: nowrap">Upload
                                File</button>
                        </div>
                    </div>
            </div>

            </form>
            <div class="text-center">
                @if (session()->has('successMsg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        @if (is_array(session('successMsg')))
                            {{ implode(', ', session('successMsg')) }}
                        @else
                            {{ session('successMsg') }}
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Loader Spinner and Alerts -->

    <!-- Modal to show uploaded data -->
    @if ($isModalOpen)
        <div x-data="{ open: false }" x-init="// Initialize modal state from Livewire
        open = @js($isModalOpen);
        // Watch for Livewire changes
        $wire.on('modalUpdated', (isOpen) => { open = isOpen });" @if ($isModalOpen)
            x-show="open"
    @endif
    x-transition
    x-cloak
    class="modal fade"
    :class="{ 'show d-block': open }"
    style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-xl">
        @if (session()->has('errorMsg'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                <strong>Error:</strong>
                @if (is_array(session('errorMsg')))
                    {{ implode(', ', session('errorMsg')) }}
                @else
                    {{ session('errorMsg') }}
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uploaded Data</h5>
                <button type="button" class="btn-close" @click="$wire.closeModal()"></button>
            </div>
            @if (!empty($uploadedData))
                <form wire:submit.prevent="processSelectedData">
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        @if (!empty($rowErrors))
                            <div class="alert alert-danger mb-3">
                                <strong>Errors Found:</strong> Please correct the highlighted errors below before
                                proceeding.
                            </div>
                        @endif
                        <div class="table-responsive tbleDiv">

                            {{--  <table class="table table-striped table-bordered ">  --}}
                            <table class="table table-hover table table-striped table-bordered  brdrclr mt-2">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="select-all-checkbox" x-data="{ isChecked: false }"
                                                x-model="isChecked"
                                                @click="isChecked = !isChecked; $dispatch('select-all', { isChecked: isChecked })">
                                            <label for="select-all-checkbox" style="margin-left: 5px;">Select
                                                All</label>
                                        </th>
                                        @foreach ($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                        <th style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody x-data
                                    @select-all.window="
                                                    event => {
                                                        const checkboxes = [...$el.querySelectorAll('input[type=checkbox]')];
                                                        const selected = [];

                                                        checkboxes.forEach(c => {
                                                            c.checked = event.detail.isChecked;
                                                            if (event.detail.isChecked) {
                                                                selected.push(c.value);
                                                            }
                                                        });

                                                        $wire.set('selectedData', event.detail.isChecked ? selected : []);
                                                    }
                                                ">
                                    @foreach ($uploadedData as $rowKey => $row)
                                        @php
                                            $hasErrors = isset($rowErrors[$rowKey]);
                                            $rowClass = $hasErrors ? 'table-danger' : '';
                                            $convertedHeaders = array_map(function ($h) {
                                                return $this->convertToSnakeCase($h);
                                            }, $headers);
                                        @endphp

                                        <tr class="{{ $rowClass }}">
                                            <td>
                                                <input type="checkbox" wire:model="selectedData"
                                                    value="{{ $rowKey }}" x-data="{ isChecked: $wire.selectedData.includes('{{ $rowKey }}') }"
                                                    x-model="isChecked"
                                                    @change="$wire.set('selectedData', 
                                                                    isChecked 
                                                                        ? [...$wire.selectedData, '{{ $rowKey }}'] 
                                                                        : $wire.selectedData.filter(id => id !== '{{ $rowKey }}'))">
                                            </td>

                                            @foreach ($headers as $index => $header)
                                                @php
                                                    $convertedHeader = $convertedHeaders[$index];
                                                    $fieldErrors =
                                                        $hasErrors && isset($rowErrors[$rowKey][$convertedHeader])
                                                            ? $rowErrors[$rowKey][$convertedHeader]
                                                            : null;
                                                @endphp

                                                <td @if ($fieldErrors) class="has-error" @endif
                                                    style="text-wrap:unset;">
                                                    {{ $row[$header] }}
                                                    @if ($fieldErrors)
                                                        <div class="error-tooltip">
                                                            <i class="bi bi-exclamation-circle text-danger"></i>
                                                            <div class="error-tooltip-text">
                                                                @if (is_array($fieldErrors))
                                                                    @foreach ($fieldErrors as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                @else
                                                                    {{ $fieldErrors }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                @if ($hasErrors)
                                                    <span class="badge bg-danger">Error</span>
                                                @elseif(in_array((string) $rowKey, $selectedData))
                                                    <span class="badge bg-success">Ready</span>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            @if (!empty($rowErrors))
                                <span class="text-danger me-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ count($rowErrors) }} row(s) have errors
                                </span>
                            @endif
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" @click="$wire.closeModal()">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>
                                Process Selected Data
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="modal-body">
                    <p class="text-muted">No data available to display.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif

<style>
    .has-error {
        position: relative;
        background-color: #fff5f5;
    }

    .error-tooltip {
        position: absolute;
        top: 2px;
        right: 2px;
        cursor: help;
    }

    .error-tooltip-text {
        visibility: hidden;
        width: 250px;
        background-color: #c73b49;
        color: white;
        text-align: center;
        border-radius: 4px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .error-tooltip:hover .error-tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    .table-danger td {
        background-color: rgba(220, 53, 69, 0.1);
    }
</style>

</div>
