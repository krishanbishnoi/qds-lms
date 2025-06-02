<link href="{{WEBSITE_CSS_URL}}admin/button.css" rel="stylesheet">
<div class="modal-dialog modal-lg email-details-modal">
	<div class="modal-content">
		<div class="modal-header">
			
			<h4 class="modal-title" id="myModalLabel">
				<?php echo trans("Email Detail"); ?>
			</h4>
			{{-- <a data-dismiss="modal" class="close" href="javascript:void(0)">
			<span  class="no-ajax" aria-hidden="true">&times;</span>
			<span class="sr-only no-ajax"></span></a>	 --}}
		</div>
		<div class="modal-body">
			<div class="mws-panel-body no-padding dataTables_wrapper">
				<table class="table table-bordered table-responsive"  >
					<tbody>
						<?php 
						if(!empty($result)){  
							foreach($result as $value){ ?>
							<tr>
								<th><?php echo trans("Email To"); ?></th>
								<td data-th='<?php echo trans("Email To"); ?>'> <?php echo $value->email_to;  ?></td>
							</tr>
							<tr>
								<th><?php echo trans("Email From"); ?></th>
								<td data-th='<?php echo trans("Email From"); ?>'><?php  echo $value->email_from; ?></td>
							</tr>
							<tr>
								<th><?php echo trans("Subject"); ?></th>
								<td data-th='<?php echo trans("Subject"); ?>'><?php echo  $value->subject; ?></td>
							</tr>
							<tr>
								<th valign='top'><?php echo trans("Messages"); ?></th>
								<td data-th='<?php echo trans("Messages"); ?>'><?php  echo  $value->message; ?></td>
							</tr>
						<?php }	} ?>
					</tbody>		
				</table>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
</div>
