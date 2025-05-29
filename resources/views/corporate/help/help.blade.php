@extends('layouts.corporate')
@section('content')
    <div class="dashboardHeading">
        <h1>Help</h1>
        <p>Welcome Back, {{ Auth::user()->name }}</p>
    </div>
    <div class="row pt-md-3">

        <div class="col-md-8">
            <div class="helpContent">
                <h2>Popular questions</h2>
                <div class="accordion" id="accordionHelp">
                    @foreach ($Faqs as $key => $Faq)
                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{$key}}"
                            aria-expanded="true" aria-controls="collapseOne">{{ Str::ucfirst($Faq->question) }}</button>
                        <div id="collapseOne_{{$key}}" class="accordion-collapse collapse {{ $key==0?'show':'' }}" data-bs-parent="#accordionHelp">
                            <div class="helpTextbox">
                                <p>{{ Str::ucfirst($Faq->answer) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <lottie-player src="../images/help.json" background="transparent" speed="1"
                style="width: 100%; height: 300px;" loop autoplay></lottie-player>
        </div>
    </div>
    <!-- <div class="managerInfo">
        <div class="managerName">
            <figure class="mb-0"><img src="../images/relationship-manager.svg" alt="" width="140"></figure>
            <div class="managerAbout">
                <h3>Relationship Manager</h3>
                <p>Branch : Lorem Ipsum</p>
            </div>
        </div>
        <div class="managerDetail">
            <h3>Manager Details</h3>
            <ul>
                <li>
                    <b>Name :</b> Leslie A. Lara
                </li>
                <li> <b> Other Detail 1 :</b> Lorem Ipsum</li>
                <li><b>Contact :</b> +91987654321</li>
                <li><b>Other Detail 2 :</b> Lorem Ipsum</li>
            </ul>
        </div>
        <div class="managerContact text-end">
            <a href="javascript:void(0);" class="btn btn-primary">Contact Now</a>
        </div>

    </div> -->
@endsection
@section('scripts')
    @parent
@endsection
