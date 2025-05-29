<!-- resources/views/api/form.blade.php -->

<form action="{{ route('api.result') }}" method="POST">
    @csrf
    <label for="text">Enter Text:</label>
    <input type="text" name="text" id="text">
    <button type="submit">Submit</button>
</form>

