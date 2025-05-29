@extends('admin.layouts.default')
@section('content')
    <div class="box search-panel collapsed-box">
        <div class="box-body mb-4">
            <form action="{{ route('import.training-participants', $training_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        <input type="file" name="file" required>
                    </div>

                </div>@auth

                @endauth
                <div class="d-md-flex justify-content-between align-items-center gap-5" style="display: block !important">
                    <button class="btn btn-primary" type="submit">Upload Users</button>
                    <a href="{{ asset('sample-files/import-training-participants-sample.xlsx') }}" class="btn btn-primary" style="margin-left:100px" > Download sample file</a>
                </div>

            </form>

        </div>
    </div>
    <script>
        function downloadFile() {
            var fileUrl = '{{ asset('sample-files/test-participants-sample.xlsx ') }}';
            console.log(fileUrl);
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = 'training-participants-sample.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection
