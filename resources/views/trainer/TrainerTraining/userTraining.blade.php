@extends('trainer.layouts.default')
@section('content')

<div class="content-wrapper">
<div class="page-header">
        <h2 class="page-title">My {{ $sectionNameSingular }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i
                            class=" fa fa-dashboard"></i>Dashboard</a></li>
           
                <li class="breadcrumb-item active">My {{ $sectionNameSingular }}</li>
            </ol>
        </nav>
    </div>
	<div class="row">
        @foreach ($myTrainings as $training)
            
        <a href="{{ route('userTrainingDetails.index',$training->id)}}">
		<div class="col-md-3 stretch-card grid-margin">
			<div class="card bg-gradient-success card-img-holder text-white">
				<div class="card-body">
                <h2 class="mb-5 mdi mdi-diamond">{{ $training->title}}</h2>
					<h4 class="font-weight-normal mb-3"> <i
							class=" mdi-24px float-right"> {{ $training->type}}</i>
					</h4>
				
					 <h6 class="card-text">{{$training->number_of_attempts }}</h6>
                     <h6 class="card-text mdi mdi-clock">{{$training->start_date_time }}</h6>
				</div>
			</div>
		</div>
        </a>
        <div class="col-md-3 stretch-card grid-margin">
			<div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <h2 class="mb-5 mdi mdi-diamond">{{ $training->title}}</h2>
					<h4 class="font-weight-normal mb-3"> <i
							class=" mdi-24px float-right"> {{ $training->type}}</i>
					</h4>
				
					 <h6 class="card-text">{{$training->number_of_attempts }}</h6>
                     <h6 class="card-text mdi mdi-clock">{{$training->start_date_time }}</h6>
				</div>
			</div>
		</div>
        <div class="col-md-3 stretch-card grid-margin">
			<div class="card bg-gradient-success card-img-holder text-white">
				<div class="card-body">
                <h2 class="mb-5 mdi mdi-diamond">{{ $training->title}}</h2>
					<h4 class="font-weight-normal mb-3"> <i
							class=" mdi-24px float-right"> {{ $training->type}}</i>
					</h4>
				
					 <h6 class="card-text">{{$training->number_of_attempts }}</h6>
                     <h6 class="card-text mdi mdi-clock">{{$training->start_date_time }}</h6>
				</div>
			</div>
		</div>
        <div class="col-md-3 stretch-card grid-margin">
			<div class="card bg-gradient-success card-img-holder text-white">
				<div class="card-body">
                <h2 class="mb-5 mdi mdi-diamond">{{ $training->title}}</h2>
					<h4 class="font-weight-normal mb-3"> <i
							class=" mdi-24px float-right"> {{ $training->type}}</i>
					</h4>
				
					 <h6 class="card-text">{{$training->number_of_attempts }}</h6>
                     <h6 class="card-text mdi mdi-clock">{{$training->start_date_time }}</h6>
				</div>
			</div>
		</div>
        @endforeach
	</div>
</div>
@stop