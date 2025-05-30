
@extends('trainer.layouts.default')

@section('content')
<div class="content-wrapper">
	<div class="row">
		<div class="col-md-4 stretch-card grid-margin">
			<div class="card bg-gradient-danger card-img-holder text-white">
				<div class="card-body">
			
					<h4 class="font-weight-normal mb-3">Total Trainers <i
							class="mdi mdi-account menu-icon mdi-24px float-right"></i>
					</h4>
					<h2 class="mb-5">{{count($totalTrainers)}}</h2>
					{{-- <h6 class="card-text">Increased by 60%</h6> --}}
				</div>
			</div>
		</div>
		<div class="col-md-4 stretch-card grid-margin">
			<div class="card bg-gradient-info card-img-holder text-white">
				<div class="card-body">
				
					<h4 class="font-weight-normal mb-3">Total Trainee <i
							class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
					</h4>
					<h2 class="mb-5">{{count($totalTrainees)}}</h2>
					{{-- <h6 class="card-text">Decreased by 10%</h6> --}}
				</div>
			</div>
		</div>
		<div class="col-md-4 stretch-card grid-margin">
			<div class="card bg-gradient-success card-img-holder text-white">
				<div class="card-body">
				
					<h4 class="font-weight-normal mb-3">Total Test Till Now <i
							class="mdi mdi-diamond mdi-24px float-right"></i>
					</h4>
					<h2 class="mb-5">0</h2>
					{{-- <h6 class="card-text">Increased by 5%</h6> --}}
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="row">
		<div class="col-md-7 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="clearfix">
						<h4 class="card-title float-left">Visit And Sales Statistics</h4>
						<div id="visit-sale-chart-legend"
							class="rounded-legend legend-horizontal legend-top-right float-right">
						</div>
					</div>
					<canvas id="visit-sale-chart" class="mt-4"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-5 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Traffic Sources</h4>
					<canvas id="traffic-chart"></canvas>
					<div id="traffic-chart-legend"
						class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
				</div>
			</div>
		</div>
	</div> -->

</div>

@endsection