<div>
    <div class="row">
        <div class="col-md-12">
            {{ Form::label('question', 'Question *', ['class' => 'mws-form-label']) }}
            {{ Form::text('question', $question, [
                'class' => 'form-control',
                'wire:model' => 'question',
            ]) }}
            @error('question')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-6 mt-3">
            {{ Form::label('question_type', 'Question Type *', ['class' => 'mws-form-label']) }}
            {{ Form::select('question_type', $questionTypes, $question_type, [
                'class' => 'form-control',
                'wire:model.live' => 'question_type',
                'wire:model' => 'question_type',
                'placeholder' => 'Select Type',
            ]) }}
            @error('question_type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-6 mt-3">
            {{ Form::label('marks', 'Marks *', ['class' => 'mws-form-label']) }}
            {{ Form::text('marks', $marks, [
                'class' => 'form-control',
                'wire:model' => 'marks',
            ]) }}
            @error('marks')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        @if (in_array($question_type, ['MCQ', 'SCQ', 'T/F']))
            <div class="col-md-12 mt-3">
                {{ Form::label(null, 'Options', ['class' => 'mws-form-label']) }}

                <div class="row g-2">
                    @foreach ($options as $index => $option)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-2">
                                    {{ Form::text("data[{$index}][option]", $option['option'], [
                                        'class' => 'form-control',
                                        'wire:model' => "options.{$index}.option",
                                    ]) }}
                                </div>

                                <div class="d-flex align-items-center">
                                    @if ($question_type === 'MCQ')
                                        <div class="form-check form-switch me-2">
                                            {{ Form::checkbox("data[{$index}][right_answer]", 1, $option['right_answer'], [
                                                'class' => 'form-check-input',
                                                'wire:model' => "options.{$index}.right_answer",
                                                'role' => 'switch',
                                            ]) }}
                                            {{ Form::label("right_answer_{$index}", 'Correct', [
                                                'class' => 'form-check-label small ms-1',
                                            ]) }}
                                        </div>
                                    @else
                                        <div class="form-check me-2">
                                            {{ Form::radio(
                                                'data_right_answer',
                                                $index,
                                                $question_type === 'SCQ' ? $selected_scq == $index : $selected_tf == $index,
                                                [
                                                    'class' => 'form-check-input',
                                                    'wire:model' => $question_type === 'SCQ' ? 'selected_scq' : 'selected_tf',
                                                    'wire:click' => "\$set('options.{$index}.right_answer', true)",
                                                ],
                                            ) }}
                                            {{ Form::label("answer_{$index}", 'Correct', [
                                                'class' => 'form-check-label small ms-1',
                                            ]) }}
                                        </div>
                                    @endif

                                    @if (!in_array($question_type, ['T/F']) && $index > 0)
                                        {{ Form::button('<i class="fas fa-trash-alt"></i>', [
                                            'type' => 'button',
                                            'wire:click' => "removeOption({$index})",
                                            'class' => 'btn btn-sm btn-outline-danger ms-2',
                                        ]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (in_array($question_type, ['MCQ', 'SCQ']))
                    <div class="mt-2">
                        {{ Form::button('<i class="fas fa-plus me-1"></i> Add More Option', [
                            'type' => 'button',
                            'wire:click' => 'addOption',
                            'class' => 'btn btn-primary btn-sm',
                        ]) }}
                    </div>
                @endif
            </div>
        @endif




    </div>
</div>
