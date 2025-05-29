@extends('admin.layouts.default')
@section('content')
<style>
    .search_container {
        margin: 0 auto;
        width: 55%;
    }

    .search_container #speechText {
        width: 80%;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .search_container #start {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .container {
        width: 55%;
        margin: 0 auto;
        border: 0px solid black;
        padding: 10px 0px;
    }

    /* post */
    .post {
        width: 97%;
        min-height: 200px;
        padding: 5px;
        border: 1px solid gray;
        margin-bottom: 15px;
    }

    .post h1 {
        letter-spacing: 1px;
        font-weight: normal;
        font-family: sans-serif;
    }


    .post p {
        letter-spacing: 1px;
        text-overflow: ellipsis;
        line-height: 25px;
    }

    /* more link */
    .more {
        color: blue;
        text-decoration: none;
        letter-spacing: 1px;
        font-size: 16px;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-primary mr-2 px-4 btn-fw" href="{{ route('Training.index') }}">Back</a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('Training.index') }}">Training</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('training.submit.ai') }}" method="POST" enctype="multipart/form-data"
                        class="forms-sample">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Enter Your Training Subject:*</label>
                            <input type="text" id="name" name="speechText" class="form-control"
                                value="" required>
                        </div>
                        <div>
                            <input class="btn btn-primary mr-2 px-4" type="submit" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('training.submit.ai') }}" method="POST" enctype="multipart/form-data"
                        class="forms-sample">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Say Me Your Training Subject:*</label>
                            <input type='button' id='start' class="btn  btn-danger me-2" value='Click Me To Start Voice' onclick='startRecording()'>
                            <input type='text' class="form-control" id='speechText' name="speechText" required > &nbsp;
                        </div>
                        <div>
                            <input class="btn btn-primary mr-2 px-4" type="submit" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var recognition = new webkitSpeechRecognition();

    recognition.onresult = function(event) {
        var saidText = "";
        for (var i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                saidText = event.results[i][0].transcript;
            } else {
                saidText += event.results[i][0].transcript;
            }
        }
        // Update Textbox value
        document.getElementById('speechText').value = saidText;

        // Search Posts
        searchPosts(saidText);
    }

    function startRecording() {
        recognition.start();
    }

    // // Search Posts
    // function searchPosts(saidText) {
    //     var url = "{{ route('training.submit.ai') }}";
    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');

    //     jQuery.ajax({
    //         url: url,
    //         type: 'post',
    //         data: {
    //             speechText: saidText,
    //             _token: csrfToken
    //         },
    //         success: function(response) {
    //             // console.log(response);
    //            window.location.href = response.redirect; // Redirect to the specified URL
    //         }
    //     });
    // }
</script>
@endsection
