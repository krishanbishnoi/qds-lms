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
                        href="{{ route($modelName.'.index',$test_id)}}">{{ $sectionName }}</a></li>
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
                                <th width="30%" class="text-right txtFntSze">Question</th>
                                <td data-th='Category Name' class="txtFntSze">{{ ucfirst($model->question) }}</td>
                            </tr>
                            <tr>
                                <th width="30%" class="text-right txtFntSze">Type</th>
                                <td data-th='Category Name' class="txtFntSze">{{ $model->question_type}}</td>
                            </tr>
                            <tr>
                                <th width="30%" class="text-right txtFntSze">Marks</th>
                                <td data-th='Category Name' class="txtFntSze">{{ $model->marks }}</td>
                            </tr>
                            <tr>
                                <th width="30%" class="text-right txtFntSze">Description</th>
                                <td data-th='Category Name' class="txtFntSze">{!! $model->description !!}</td>
                            </tr>

                            <tr>
                                <th width="30%" class="text-right txtFntSze">Option</th>
                                @if(!empty($attribute))
                                    @foreach ($attribute as $option)
                                        <td data-th='Category Name'>{{ ucfirst($option->option) }}
                                        @if($option->is_correct == 1)
                                                <p style="color: green">(Right Option)</p>
                                        @endif

                                        </td>

                                        <br>
                                    @endforeach
                                @endif
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
<style>
.padding {
    padding-top: 20px;
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
