@extends('admin.layouts.default')
@section('content')
    <script>
        jQuery(document).ready(function() {
            $('#start_from').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_to').datetimepicker({
                format: 'YYYY-MM-DD'
            });

        });
    </script>
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                View {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('admin/dashboard') }}">
                            Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="{{ route($modelName . '.index', $training_id) }}">{{ $sectionName }}</a></li>

                    <li class="active"> / View {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover brdrclr" width="100%">
                            <tbody>


                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Training Title</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ ucfirst($training->title) }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Course Title</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->title }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Start Date Time</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->start_date_time }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">End Date Time</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->end_date_time }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Description</th>
                                    <td data-th='Category Name' class="txtFntSze">{!! ucfirst($model->description) !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Course Docuements</h3>

                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-res-common">
                                    <table class="table table-hover brdrclr" width="100%">
                                        <thead>
                                            <tr>


                                                <th>{{ trans('SN.') }}</th>

                                                <th>{{ trans('Title') }}</th>
                                                <th>{{ trans('Document Type') }}</th>
                                                <th>{{ trans('Document') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody id="powerwidgets">
                                            <?php $number = 1; ?>
                                            @if (!empty($course_documents))
                                                @foreach ($course_documents as $course_document)
                                                    <tr class="items-inner">
                                                        <td>{{ $number++ }}</td>
                                                        <td>{{ $course_document->title }}</td>
                                                        <td>{{ $course_document->document_type }}</td>

                                                        <td width="50%">

                                                            @if ($course_document->type == 'image')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    <img height="50%" width="50%"
                                                                        src="{{ TRAINING_DOCUMENT_URL . $course_document->document }}" />
                                                                @endif
                                                            @elseif ($course_document->type == 'doc' && $course_document->document_type == 'pdf')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    <iframe
                                                                        src="{{ TRAINING_DOCUMENT_URL . $course_document->document }}"
                                                                        style="width: 100%; height: 500px;"></iframe>
                                                                @endif
                                                            @elseif (
                                                                ($course_document->type == 'doc' && $course_document->document_type == 'ppt') ||
                                                                    $course_document->document_type == 'pptx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    {{-- Using view.officeapps.live.com iframe --}}
                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $course_document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif (
                                                                ($course_document->type == 'doc' && $course_document->document_type == 'xls') ||
                                                                    $course_document->document_type == 'xlsx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $course_document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif (
                                                                ($course_document->type == 'doc' && $course_document->document_type == 'doc') ||
                                                                    $course_document->document_type == 'docx')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    <iframe
                                                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('training_document/' . $course_document->document) }}"
                                                                        style="width: 100%; height: 500px;" frameborder="0">
                                                                    </iframe>
                                                                @endif
                                                            @elseif($course_document->type == 'video')
                                                                @if (config('TRAINING_DOCUMENT_URL') . $course_document->document != '')
                                                                    <br />

                                                                    <video height="50%" width="50%" controls>

                                                                        <source
                                                                            src="{{ TRAINING_DOCUMENT_URL . $course_document->document }}"
                                                                            type="video/mp4">
                                                                        <!-- <source
                                                                                src="{{ TRAINING_DOCUMENT_URL . $course_document->document }}"
                                                                                type="video/mp4"> -->

                                                                        Your browser does not support the video tag.

                                                                    </video>
                                                                @endif
                                                            @endif



                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .padding {
            padding-top: 20px;
        }

        .table th img,
        .table td img {

            width: 50%;
            height: 50%;
            border-radius: 0%;

        }
    </style>
    <script>
        // $('.confirm').on('click', function (e) {
        //         if (confirm($(this).data('confirm'))) {
        //             return true;
        //         }
        //         else {
        //             return false;
        //         }
        //     });

        $(function() {
            $(document).on('click', '.delete_any_item', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to delete this ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });

            /**
             * Function to change status
             *
             * @param null
             *
             * @return void
             */
            $(document).on('click', '.status_any_item', function(e) {


                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to change status ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });
        });
    </script>
@stop
