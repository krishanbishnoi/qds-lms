<!-- resources/views/api/result.blade.php -->
@extends('admin.layouts.default')
@section('content')


	<div class="content-wrapper">
		<div class="page-header">
			<h1>
				{{ $sectionName }}
			</h1>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
						Dashboard</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>

				</ol>
			</nav>
		</div>

		<div class="row">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
		<div class="card-header">
			<h1>Training Content</h1>
		</div>
		<div class="card-body">
			{{-- Access the 'generated_text' from the 'openai' section --}}
			{{-- <p>{{ $responseData['openai']['generated_text'] }}</p> --}}
			{{-- <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT) }}</pre> --}}
			<p>{!! $jsonData['google']['generated_text']!!}</p>
			<p>{{ $html }}</p>



			{{-- <p>{{ $responseData['google']['generated_text'] }}</p> --}}
		</div>
	</div>

@endsection
