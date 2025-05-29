
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Training Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if(!empty($result))
            @foreach($result as $result)

            <strong class="mb-2">Training Name</strong>
            <p id="trainingTitle">{{$result->title}}</p>
            <strong class="mt-3  mb-2">About this Training</strong>
            <p id="trainingDescription">{!!$result->description!!}</p>
            <strong class="mt-3 mb-2">Other Details</strong>
            <p>Training Type : {{$result->type}}</p>
            <p>Trainee : {{$result->number_of_attempts}}</p>
            <p>Total Content : {{$result->number_of_attempts}}</p>
            <p>Total Time to finish : {{\Carbon\Carbon::parse($result->end_date_time)->diff(\Carbon\Carbon::parse($result->start_date_time))->format('%a')}} Days </p>
            <strong class="mt-3 mb-2">Certificates</strong>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
            <p>Complete the training and test to achieve this certificate</p>
            <span class="btn btn-light py-2 px-3 fs-7 mt-3">Certificate</span>
            @endforeach
            @endif

        </div>
    </div>
</div>
