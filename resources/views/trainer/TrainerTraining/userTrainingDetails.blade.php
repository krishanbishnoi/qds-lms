@extends('trainer.layouts.default')
@section('content')

<div class="content-wrapper">
	<div class="page-header">
		<h1>
		Training Details
		</h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ URL::to('admin/dashboard') }}">
					Dashboard</a>
				</li>
				<li class="breadcrumb-item">
						Training Details
				</li>
				<li class="active"> Training Details</li>
			</ol>
		</nav>
	</div>
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<table class="table table-hover brdrclr" width="100%">
						<tbody>
							<tr>
								<th width="30%" class="text-right txtFntSze">Title</th>
								<td data-th='Name' class="txtFntSze">
									{{ isset($trainingDetails->title) ? $trainingDetails->title : '' }}
								</td>
							</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Type</th>
								<td data-th='Name' class="txtFntSze">
									{{ isset($trainingDetails->type) ? $trainingDetails->type : '' }}
								</td>
							</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Number of Attempts</th>
								<td data-th='Name' class="txtFntSze">
									{{ isset($trainingDetails->number_of_attempts) ? $trainingDetails->number_of_attempts : '' }}
								</td>
							</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Start Date Time</th>
								<td data-th='Name' class="txtFntSze">
									{{ isset($trainingDetails->start_date_time) ? $trainingDetails->start_date_time : '' }}

								</td>
							</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Minimum Marks</th>
								<td data-th='Name' class="txtFntSze">
									{{ isset($trainingDetails->minimum_marks) ? $trainingDetails->minimum_marks : '' }}
								</td>
							</tr>
							<tr>
								<th width="30%" class="text-right txtFntSze">Document</th>
								<td data-th='Name' class="txtFntSze">
								<iframe src=
									"https://media.geeksforgeeks.org/wp-content/cdn-uploads/20210101201653/PDF.pdf"
													width="800"
													height="500">
											</iframe>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>

</div>
@stop
