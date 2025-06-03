<div class="content-wrapper">
    <!-- Load jQuery and Select2 first -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="p-4">
                        <!-- Project Selection -->
                        <div class="col-md-6">
                            {!! Form::label('project', 'Select Project', ['class' => 'block font-bold mb-1']) !!}
                            {!! Form::select('project', $projects, null, [
                                'wire:model' => 'project',
                                'wire:change' => 'projectChange',
                                'class' => 'form-control',
                                'placeholder' => '-- Choose Project --',
                            ]) !!}
                        </div>

                        @if ($projectName === 'RetailIQ')
                            <div class="col-md-6">
                                <label for="auction_id" class="block font-bold mb-1">Auction ID</label>
                                <input type="text" wire:model="auction_id" id="auction_id"
                                    class="border rounded p-2 w-full" placeholder="Enter Auction ID">
                            </div>
                        @elseif($projectName && $projectName !== 'RetailIQ')
                            <div class="col-md-6">
                                {!! Form::label('method', 'Select Method', ['class' => 'block font-bold mb-1']) !!}
                                {!! Form::select('method', $methods, null, [
                                    'wire:model' => 'method',
                                    'wire:change' => 'methodChange',
                                    'class' => 'form-control',
                                    'placeholder' => '-- Choose Method --',
                                ]) !!}
                            </div>
                        @endif

                        @if ($methodName === 'fromExcel')
                            <div class="box search-panel collapsed-box">
                                <div class="box-body mb-4">
                                    <form action="{{ route('import.training-participants', $training_id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <input type="file" name="file" required>
                                            </div>
                                        </div>

                                        <div class="d-md-flex justify-content-between align-items-center gap-5"
                                            style="display: block !important">
                                            <button class="btn btn-primary" type="submit">Upload Users</button>
                                            <a href="{{ asset('sample-files/import-training-participants-sample.xlsx') }}"
                                                class="btn btn-primary" style="margin-left:100px">
                                                Download sample file
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @elseif($methodName === 'fromUser')
                            <!-- Future condition for fromUser -->
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Select2 Multi-select -->
        <div class="col-md-6 mt-4" wire:ignore>
            {!! Form::label('user_ids', 'Select Users', ['class' => 'block font-bold mb-1']) !!}
            {!! Form::select('user_ids[]', $methods, null, [
                'class' => 'form-control select2-form',
                'multiple' => 'multiple',
                'id' => 'select2-users',
            ]) !!}
        </div>
    </div>

    <!-- Select2 Init Script -->
    <script>
        function initSelect2() {
            $('#select2-users').select2({
                width: '100%',
                placeholder: 'Select users...',
                allowClear: true,
            });

            // Optional: Hook into change event to notify Livewire manually
            $('#select2-users').on('change', function() {
                @this.set('userSelects', $(this).val());
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });

        // Optional: re-init if Livewire updates the DOM
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });
    </script>
</div>
