@extends('admin.layouts.default')
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Edit Email Template</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
                <li class="breadcrumb-item" ><a href="{{ route($modelName.'.index')}}">Email Template</a></li>
                <li class="breadcrumb-item active" >Edit Email Template</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                {{ Form::open(['role' => 'form','url' => 'admin/email-manager/edit-template/'.$emailTemplate->id,'class' => 'mws-form']) }}
                    <div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
                        {!! Html::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>',
                        ['class' => 'mws-form-label'])) !!}
                        <div class="mws-form-item">
                            {{ Form::text('name', $emailTemplate->name, ['class' => 'form-control']) }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('name'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('subject')?'has-error':''); ?>">
                        {!! Html::decode( Form::label('subject',trans("Subject").'<span class="requireRed"> * </span>',
                        ['class' => 'mws-form-label'])) !!}
                        <div class="mws-form-item">
                            {{ Form::text('subject', $emailTemplate->subject, ['class' => 'form-control']) }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('subject'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display:none;">
                        {!! Form::label('action', trans("Action"), ['class' => 'mws-form-label']) !!}
                        <div class="mws-form-item">
                            {{ Form::select('action',$Action_options,$emailTemplate->action, ['class' => 'form-control','onchange'=>'constant()']) }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('action'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php echo ($errors->first('constants')?'has-error':''); ?>">
						<table class="table  table-responsive">
							<tr>
								<td colspan="2" >
									{!! Html::decode( Form::label('constants',trans("Constants").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								</td>
							</tr>
							<tr>
								<td>
									{{ Form::select('constants', array(),'', ['empty' => 'Select one','class' => 'form-control','id'=>'constants']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('constants'); ?>
									</div>
								</td>
								<td>
								</td>
									<td>
									<a onclick = "return InsertHtml()" href="javascript:void(0)" class="btn  btn-success no-ajax pull-right"><i class="icon-white "></i>{{  trans("Insert Variable") }} </a>
								</td>
							</tr>

						</table>
					</div>
                    <div class="form-group <?php echo ($errors->first('body')?'has-error':''); ?>">
                        {!! Html::decode( Form::label('body',trans("Email Body").'<span class="requireRed"> * </span>',
                        ['class' => 'mws-form-label'])) !!}
                        <div class="mws-form-item">


                            {{ Form::textarea("body",$emailTemplate->body, ['class' => 'form-control','id' => 'body']) }}
                            <span class="error-message help-inline">
                                <?php echo $errors->first('body'); ?>
                            </span>
                        </div>

                        <script>
                        var body = CKEDITOR.replace('body', {
                            extraAllowedContent: 'div',
                            height: 300
                        });
                        body.on('instanceReady', function() {
                            // Output self-closing tags the HTML4 way, like <br>.
                            this.dataProcessor.writer.selfClosingEnd = '>';

                            // Use line breaks for block elements, tables, and lists.
                            var dtd = CKEDITOR.dtd;
                            for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd
                                    .$listItem, dtd.$tableContent)) {
                                this.dataProcessor.writer.setRules(e, {
                                    indent: true,
                                    breakBeforeOpen: true,
                                    breakAfterOpen: true,
                                    breakBeforeClose: true,
                                    breakAfterClose: true,
                                    filebrowserUploadUrl: '<?php echo URL::to('/admin/base/uploder'); ?>',
                                    filebrowserImageWindowWidth: '640',
                                    filebrowserImageWindowHeight: '480',
                                });
                            }
                            // Start in source mode.
                            //this.setMode('source');
                        });
                        </script>
                    </div>
                    <div class="mws-button-row">
                        <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">

                        <a href="{{URL::to('/admin/email-manager/edit-template/'.$emailTemplate->id)}}"
                            class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>

                        <a href="{{URL::to('/admin/email-manager')}}" class="btn btn-info"><i
                                class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
                    </div>
                    {{ Form::close() }}
            </div>
        </div>
    <div>
<div>


<?php  $constant = ''; ?>
<script type='text/javascript'>
var myText = '<?php  echo $constant; ?>';
	$(function(){
		constant();
	});
	/* this function used for  insert contant, when we click on  insert variable button */
    function InsertHtml() {

		var strUser = document.getElementById("constants").value;

		if(strUser != ''){
			var newStr = '{'+strUser+'}';
			var oEditor = CKEDITOR.instances["body"] ;
			oEditor.insertHtml(newStr) ;
		}
    }
	/* this function used for get constant,define in email template*/
	function constant() {
		var constant = document.getElementById("action").value;
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
					},
				url: "<?php echo URL::to('/admin/email-manager/get-constant')?>",
				type: "POST",
				data: { constant: constant},
				dataType: 'json',
				success: function(r){
					$('#constants').empty();
					$('#constants').append( '<option value="">-- Select One --</option>' );
					$.each(r, function(val, text) {
						var sel ='';
						if(myText == text)
						 {
						   sel ='selected="selected"';
						 }

						$('#constants').append( '<option value="'+text+'" '+sel+'>'+text+'</option>');
					});
			   }
			});
		return false;
	}
</script>
<style>
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		font-size: 14px !important;
		padding: 0px !important;
	}
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		vertical-align: top !important;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
		border: 0px !important;
	}
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		border-top: 0px !important;
		padding: 0px !important;
	}
	.table-bordered {
		border: 0px !important;
	}
</style>
@stop

