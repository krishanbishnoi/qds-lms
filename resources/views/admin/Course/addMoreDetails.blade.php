<div class="projectDetailsInnerSection_<?php echo $offset; ?> ace_left_sec">
    <table>
        <tr>


            <td width="700px">
                <div class="form-group <?php echo $errors->first('title') ? 'has-error' : ''; ?>">
                    {!! Html::decode(
                        Form::label('title', trans('Title') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label']),
                    ) !!}
                    <div class="mws-form-item">
                        {{ Form::text('data[' . $offset . '][title]', '', ['class' => 'form-control title']) }}
                        <div class="error-message help-inline">
                            <?php echo $errors->first('title'); ?>
                        </div>
                    </div>
                </div>
            </td>
            <td width="700px">
                <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                    {!! Html::decode(
                        Form::label('document', trans('Document') . '<span class="requireRed">  </span>', ['class' => 'mws-form-label']),
                    ) !!}
                    <div class="mws-form-item">
                        {{ Form::file('data[' . $offset . '][document]', ['class' => 'form-control document']) }}
                        <div class="error-message help-inline">
                            <?php echo $errors->first('document'); ?>
                        </div>
                    </div>
                </div>
            </td>
            <td width="700px">
                <div class="form-group <?php echo $errors->first('length') ? 'has-error' : ''; ?>">
                    {!! Html::decode(
                        Form::label('length', trans('Reading Time (In Minutes)') . '<span class="requireRed">  </span>', [
                            'class' => 'mws-form-label',
                        ]),
                    ) !!}
                    <div class="mws-form-item">
                        {{ Form::text('data[' . $offset . '][length]', '', ['class' => 'form-control length']) }}
                        <div class="error-message help-inline">
                            <?php echo $errors->first('length'); ?>
                        </div>
                    </div>
                </div>
            </td>

            <td style=" padding-top: 20px;" width="25px">
                <div class="form-group">

                    <a href="javascript:void(0);" class="btn btn-success position-right"
                        onclick="removeSection('<?php echo $offset; ?>')" id="remove_<?php echo $offset; ?>">Remove</a>
                </div>
            </td>
        </tr>
    </table>
</div>
