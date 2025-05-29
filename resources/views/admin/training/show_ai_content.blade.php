<!-- resources/views/api/result.blade.php -->
@extends('layouts.admin')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>Training Content</h1>
        </div>
        <div class="card-body">
            {{-- Access the 'generated_text' from the 'openai' section --}}
            {{-- <p>{{ $responseData['openai']['generated_text'] }}</p> --}}
            {{-- <pre>{{ json_encode($jsonData, JSON_PRETTY_PRINT) }}</pre> --}}
            <p>{{ $jsonData['google']['generated_text'] }}</p>


            {{-- <p>{{ $responseData['google']['generated_text'] }}</p> --}}
        </div>
    </div>
</div>
@endsection
