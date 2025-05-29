<div class="projectDetailsInnerSection_<?php echo $offset;?> ace_left_sec" >
	<table>
		<tr>
			
			
			<td width="700px">
				<div class="form-group <?php echo ($errors->first('option')) ? 'has-error' : ''; ?>">
					{!! Html::decode( Form::label('option', trans("Option").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('data['.$offset.'][option]','',['class'=>'form-control option']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('option'); ?>
						</div>
					</div>
				</div>
			</td>
			<td width="700px">
				<div
					class="form-group <?php echo ($errors->first('right_answer')) ? 'has-error' : ''; ?>">
					{!! Html::decode( Form::label('right_answer', trans("Right Answer").'<span
						class="requireRed"> </span>', ['class' => 'mws-form-label']))
					!!}
					<div class="mws-form-item">
						{{ Form::checkbox('data['.$offset.'][right_answer]', '1', false, ['class' => 'form-check-input ']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('right_answer'); ?>
						</div>
					</div>
				</div>
			</td>
			<td style=" padding-top: 20px;" width="25px">
				<div class="form-group">
				
				<a href="javascript:void(0);" class="btn btn-success position-right" onclick="removeSection('<?php echo $offset;?>')" id="remove_<?php echo $offset;?>">Remove</a>
				</div>
			</td>
		</tr>
	</table>
</div>