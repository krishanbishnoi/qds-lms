<div class="projectDetailsInnerSection_<?php echo $offset;?> ace_left_sec" >
	<table>
		<tr>
			
			<td width="300px">
				<div class="form-group <?php echo ($errors->first('position')) ? 'has-error' : ''; ?>">
					{!! Html::decode( Form::label('position', trans("Position").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('data['.$offset.'][position]',$offset,['class'=>'form-control','readonly'=>'readonly','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('position'); ?>
						</div>
					</div>
				</div>
			</td>
			<td width="300px">
				<div class="form-group <?php echo ($errors->first('amount')) ? 'has-error' : ''; ?>">
					{!! Html::decode( Form::label('amount', trans("Amount").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('data['.$offset.'][amount]','',['class'=>'form-control','maxlength'=>"10", 'oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');"]) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('amount'); ?>
						</div>
					</div>
				</div>
			</td>
			<td style=" padding-top: 20px;" width="300px">
				<div class="form-group">
				
				<a href="javascript:void(0);" class="btn btn-danger position-right" onclick="removeSection('<?php echo $offset;?>')" id="remove_<?php echo $offset;?>">Remove</a>
				</div>
			</td>
		</tr>
	</table>
</div>