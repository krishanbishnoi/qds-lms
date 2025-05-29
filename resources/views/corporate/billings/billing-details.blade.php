@extends('layouts.corporate')
@section('content')
    <!-- USE FOR MOBILE START-->
    <!-- <div class="dashboardHeading d-lg-none">
                    <h2>Dashboard</h2>
                    <p>Welcome Back, Joey Miller</p>
                </div> -->
    <!-- USE FOR MOBILE END-->
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="dashboardHeading">
                <h2 class="mb-4">My Billing Details</h2>
                <div class="SubmitDetail box">
                    <h6 class="text-black mb-3 fw-semibold">Add GST</h6>
                    {{ Form::open(['uri' => route('billing-detail.store'), 'method' => 'post', 'id' => 'addBillingDetails']) }}
                    <div class="submitContent">
                        <div class="mb-3">
                            <label class="form-label mb-0">Entry Name</label>
                            <div class="input-group">
                                <input name="entity_name" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="Qdegrees Services Pvt. Ltd.">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">GST number</label>
                            <div class="input-group">
                                <input name="gst_number" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="00000000000000 ">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">Address</label>
                            <div class="input-group">
                                <input name="address_1" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="B-9, 1st Floor, Mahalaxmi Nagar Rd, behind WTP">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">Address Line 2</label>
                            <div class="input-group">
                                <input name="address_2" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="South Block, Malviya Nagar, Jaipur, Rajasthan 302017">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">Country</label>
                            <div class="input-group">
                                <input name="country" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="India">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">State</label>
                            <div class="input-group">
                                <input name="state" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="Rajasthan">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">City</label>
                            <div class="input-group">
                                <input name="city" type="text"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="Jaipur">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">Pincode</label>
                            <div class="input-group">
                                <input name="pincode" type="number"
                                    class="form-control requiredField border-0 border-bottom border-dark ps-0"
                                    placeholder="302020">
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('billing-detail.index') }}"><button type="submit"
                                    class="btn btn-primary w-75 my-3">Submit</button></a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <?php $i = 1; ?>
            @foreach ($BillingDetailsList as $BillingDetails)
                @if ($BillingDetails->is_default)
                    <div class="applyFilter d-flex justify-content-end mt-3">
                        <span class="default-entry">Default Entity</span>
                    </div>
                @endif
                <div class="row bg-body-tertiary p-2 justify-content-between box mb-3">
                    <h6 class="text-black fw-semibold mb-3">Entity {{ $i }}</h6>
                    <div class="col-md-4">
                        <div class="EntryBilling">
                            <div>
                                <span class="mb-1 d-block">Entity Name</span>
                                <h6>{{ $BillingDetails->entity_name }}</h6>
                            </div>
                            <div>
                                <span class="mb-1 d-block">GST number</span>
                                <h6>{{ $BillingDetails->gst_number }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="EntryBilling address">
                            <div>
                                <span class="mb-1 d-block">Address</span>
                                <p class="fw-normal text-black ">
                                    {{ $BillingDetails->address_1 . ', ' . $BillingDetails->address_2 . ', ' . $BillingDetails->city . ', ' . $BillingDetails->state . ', ' . $BillingDetails->country . ', ' . $BillingDetails->pincode }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex flex-column gap-3">
                            <button id="editEntity" class="btn btn-primary" data-bs-target="#Edit-Modal-{{ $i }}"
                                data-bs-toggle="modal">Edit</button>
                            @if($BillingDetails->is_default == false)
                            <button id="deleteEntity" class="btn btn-danger" data-bs-target="#Delete-Modal-{{ $i }}"
                                data-bs-toggle="modal">Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal fade editModal" id="Edit-Modal-{{ $i }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered successfullyModal">
                        <div class="modal-content rounded-0 ">
                            <div class="modal-header border-0 pb-1">
                                <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Edit GST Details</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body ">
                                {{ Form::open(['uri' => route('billing-detail.update', [$BillingDetails->id]), 'method' => 'post', 'id' => 'editBillingDetails']) }}
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $BillingDetails->id }}">
                                <div class="SubmitDetail bg-white">
                                    <!-- <h6 class="text-black mb-3 fw-semibold">Add GST</h6> -->
                                    <div class="submitContent ">
                                        <div class="mb-3">
                                            <label class="form-label mb-0">Entry Name</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->entity_name }}" name="entity_name"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">GST number</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->gst_number }}" name="gst_number"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">Address</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->address_1 }}" name="address_1"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">Address Line 2</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->address_2 }}" name="address_2"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">Country</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->country }}" name="country"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">State</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->state }}" name="state"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">City</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->city }}" name="city" type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-0">Pincode</label>
                                            <div class="input-group">
                                                <input value="{{ $BillingDetails->pincode }}" name="pincode"
                                                    type="text"
                                                    class="form-control requiredField border-0 border-bottom border-dark ps-0">
                                            </div>
                                        </div>
                                        @if($BillingDetails->is_default != 1)
                                        <div class="form-group mb-3">
                                            <div class="cstmCheckbox">
                                                <input {{ $BillingDetails->is_default == 1 ? 'checked' : '' }}
                                                    name="is_default" type="checkbox" id="termCheck-{{$i}}">
                                                <label for="termCheck-{{$i}}" class="pt-1 text-black">Make Default Entity</label>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-75 mt-3 updateEntityBtn">Save</button>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="Delete-Modal-{{ $i }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered successfullyModal">
                    <div class="modal-content rounded-0 ">
                        <div class="modal-header border-0">
                            <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Edit GST Details</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <div class=" text-center">
                                <lottie-player src="../images/error.json" class="mx-auto" background="transparent"
                                    speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                            </div>
                            <h6 class="text-black text-center">Are You Sure ?</h6>
                            <p class="fw-mediam text-black text-center">Do you really want to delete these records?</p>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary w-100 ">Cancel</button>
                                {{ Form::open(['uri' => route('billing-detail.destroy', $BillingDetails->id), 'method' => 'post', 'id' => 'deleteBillingDetails','class'=>'w-100']) }}
                                                    <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger w-100">Delete</button>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <?php $i++; ?>
            @endforeach
        </div>
    </div>
    <div class="menu-overly"></div>
    <div class="modal fade" id="Submit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered successfullyModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1> -->
                    <a class="btn-close" href="{{ route('billing-detail.index') }}"></a>
                </div>
                <div class="modal-body text-center">
                    <div class=" text-center">
                        <lottie-player src="../images/successfully-send.json" class="mx-auto" background="transparent"
                            speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                    </div>
                    <p class="fw-semibold text-black">You have successfully add entity</p>
                    <a href="{{ route('billing-detail.index') }}"><button class="btn btn-primary w-75">Add More</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Edited" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered successfullyModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1> -->
                    <a class="btn-close" href="{{ route('billing-detail.index') }}"></a>
                </div>
                <div class="modal-body text-center">
                    <div class=" text-center">
                        <lottie-player src="../images/successfully-send.json" class="mx-auto" background="transparent"
                            speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                    </div>
                    <p class="fw-semibold text-black">You have successfully edited entity</p>
                    <a href="{{ route('billing-detail.index') }}"><button class="btn btn-primary w-75">Done</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Error" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered successfullyModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1> -->
                    <a class="btn-close" href="{{ route('billing-detail.index') }}"></a>
                </div>
                <div class="modal-body text-center">
                    <div class=" text-center">
                        <lottie-player src="../images/error.json" class="mx-auto" background="transparent"
                                    speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                    </div>
                    <p class="fw-semibold text-black">This process not completed</p>
                    <a href="{{ route('billing-detail.index') }}"><button class="btn btn-primary w-75">Okay</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Deleted" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered successfullyModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1> -->
                    <a class="btn-close" href="{{ route('billing-detail.index') }}"></a>
                </div>
                <div class="modal-body text-center">
                    <div class=" text-center">
                        <lottie-player src="../images/successfully-send.json" class="mx-auto" background="transparent"
                            speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                    </div>
                    <p class="fw-semibold text-black">You have successfully deleted entity</p>
                    <a href="{{ route('billing-detail.index') }}"><button class="btn btn-primary w-75">Done</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        jQuery(document).ready(function($) {
            $(document).on('submit', '#addBillingDetails', function(e) {
                e.preventDefault();
                var isErorr = false;
                jQuery('#addBillingDetails input.requiredField').each(function() {
                    if (!jQuery(this).val()) {
                        isErorr = true;
                        jQuery(this).closest('div').find('input').addClass('invalid_bbc');
                    } else {
                        jQuery(this).closest('div').find('input').removeClass('invalid_bbc');
                    }
                });
                if (isErorr) {
                    return;
                }

                var formData = new FormData(this);
                if (formData) {
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: '{{ route('billing-detail.store') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            $("#Submit").modal('show');
                            // window.location.replace('/admin/booking-manager/');
                        },
                        error: function(err) {
                        }
                    });
                }
            });

            $(document).on('submit', '#editBillingDetails', function(e) {
                e.preventDefault();
                var isErorr = false;
                jQuery('.updateForm input.requiredField').each(function() {
                    if (!jQuery(this).val()) {
                        isErorr = true;
                        jQuery(this).closest('div').find('input').addClass('invalid_bbc');
                    } else {
                        jQuery(this).closest('div').find('input').removeClass('invalid_bbc');
                    }
                });
                if (isErorr) {
                    return;
                }

                var formData = new FormData(this);
                var uri = jQuery(this).closest('form').attr('uri');
                if (formData) {
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: uri,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            $('.modal').modal('hide')
                            $("#Edited").modal('show');
                        },
                        error: function(err) {
                        }
                    });
                }
            });

            $(document).on('submit', '#deleteBillingDetails', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var uri = jQuery(this).closest('form').attr('uri');
                if (formData) {
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: uri,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('.modal').modal('hide');
                            if(response.status == false){
                                $("#Error").find('.text-black').text(response.message);
                                $("#Error").modal('show');
                            }else{
                                $("#Deleted").modal('show');
                            }
                        },
                        error: function(err) {
                        }
                    });
                }
            });
        });
    </script>
@endsection
