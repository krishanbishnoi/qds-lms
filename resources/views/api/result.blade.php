<!-- resources/views/api/result.blade.php -->


<div class="card">
    <div class="card-header">
        <h1>API Result:</h1>
    </div>
    <div class="card-body">
        {{-- Access the 'generated_text' from the 'openai' section --}}
        <p>{{ $responseData['openai']['generated_text'] }}</p>
    </div>
</div>





