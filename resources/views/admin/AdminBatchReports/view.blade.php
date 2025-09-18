@extends('admin.layouts.default')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

                    <a href="{{ URL::to('trainer/dashboard') }}">

                        Dashboard</a>

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route($modelName.'.index')}}">

                        Users</a>

                </li>

                <li class="active"> / View {{ $sectionNameSingular }}</li>

            </ol>

        </nav>

    </div>

    <div class="row">

        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">

                    <table class="table table-hover" width="100%">

                        <tbody>
                            <tr>

                                <th width="30%" class="text-right txtFntSze">Report Name</th>

                                <td data-th='Category Name' class="txtFntSze">{{ ucfirst($model->report_name) }}</td>

                            </tr>

                            <tr>

                                <th width="30%" class="text-right txtFntSze">Created By</th>

                                <td data-th='Category Name' class="txtFntSze">{{ $createdByName }}</td>

                            </tr>


                            <tr>

                                <th width="30%" class="text-right txtFntSze">Report Type</th>

                                <td data-th='Category Name' class="txtFntSze">{{ $model->report_type }}</td>

                            </tr>
                            <tr>

                                <th width="30%" class="text-right txtFntSze">Report Date</th>

                                <td data-th='Category Name' class="txtFntSze">{{ $model->report_from }}</td>

                            </tr>
                            {{--  <tr>

                                <th width="30%" class="text-right txtFntSze">Report to</th>

                                <td data-th='Category Name' class="txtFntSze">{{ $model->report_to }}</td>

                            </tr>  --}}
                            <tr>
                                <th width="30%" class="text-right txtFntSze">
                                    Report File
                                </th>
                                    <td data-th="Category Name" class="txtFntSze">{{ $model->document_type }}</td>
                                    <td data-th="Category Name" class="txtFntSze">
                                        <a href="{{ route('AdminBatchReports.fileDownload', ['id' => $model->id]) }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>




                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


<script>

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
        $(document).on('click', '.download_any_item', function(e) {

            e.stopImmediatePropagation();

            url = $(this).attr('href');

            bootbox.confirm("Are you  want to download this ?",

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
