@extends('layouts.frontend')

@section('content')
<section class="innerBanner" >
    <div class="container">
        <div class="bannerTxt pb-0 mw-100">
            <h1>Contact us</h1>
            <p>Get personalized account and expense management services for online business travel booking.</p>
        </div>
    </div>
</section>
<section class="contactSection pt-lg-3">
    <div class="traingleImg">
        <img src="images/triangle-img.svg" alt="img" width="260" height="260">
    </div>
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-6 pb-5">
                <div class="heading text-start">
                    <h2>Get ready to enjoy the freedom of booking your own business trip</h2>
                </div>
                <figure><img src="images/contact-img.png" alt=""></figure>
            </div>
            <div class="col-lg-6">
                <div class="messageForm">
                    <h2>Send Us message</h2>
                    {{ Form::open(array('url' => '')) }}
                        <div class="form-group">
                            <div class="form-floating">
                                {{ Form::email('email','',array('class'=>'form-control','placeholder'=>'Your Email*')) }}
                                {{ Form::label('email', 'Your Email*',array('class'=>'floatLabel')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-floating">
                                {{ Form::text('email','',array('class'=>'form-control','placeholder'=>'Your Name*')) }}
                                {{ Form::label('email', 'Your Name*',array('class'=>'floatLabel')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-floating">
                                {{ Form::text('phone_number','',array('class'=>'form-control','placeholder'=>'Phone Number*')) }}
                                {{ Form::label('phone_number', 'Phone Number*',array('class'=>'floatLabel')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-floating">
                                {{ Form::email('subject','',array('class'=>'form-control','placeholder'=>'Subject')) }}
                                {{ Form::label('subject', 'Your Email*',array('class'=>'floatLabel')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-floating">
                                {{ Form::textarea('message','',array('class'=>'form-control','placeholder'=>'Your Message')) }}
                                {{ Form::label('message', 'Your Message',array('class'=>'floatLabel')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="cstmCheckbox">
                                {{ Form::checkbox('termCheck','',false,array('id'=>'termCheck')) }}
                                {{ Form::label('termCheck', 'By sending this message, you confirm that you have read and
                                agreed to our
                                privacy-policy.') }}
                            </div>
                        </div>
                        <div class="sendBtn">
                            {{ Form::submit('Get Started',array('class'=>'getStartedBtn')) }}
                        </div>
                        {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@stop
