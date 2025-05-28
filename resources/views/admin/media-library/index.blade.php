@extends('layouts.admin')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            @can('media_library_create')
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <a class="btn btn-primary" data-toggle="collapse" href="#uploadMediaSection" role="button"
                            aria-expanded="false" aria-controls="uploadMediaSection">
                            Upload Media
                        </a>

                    </div>
                </div>
            @endcan
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Media Library Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="collapse mt-3" id="uploadMediaSection">
                <form id="mediaLibrary" action="{{ route('admin.media-library.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="needsclick dropzone" id="mediaLibrary-dropzone">
                        <div class="dz-message" data-dz-message>
                            <span>Click/Drop files here to upload<br><i>Up to 30 images in gallery<br> (Image size-
                                    5MB)</i></span>
                        </div>
                    </div>
                    <input class="btn btn-primary m-3 float-right" type="submit" value="Upload">
                </form>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Corporate Admin {{ trans('global.list') }}</h4>
                        <div class="table-responsive">
                            <table class="table table-striped datatable-Hotels" id="datatable-crud">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th class="title">Title</th>
                                        <th class="preview">Preview</th>
                                        <th>Date</th>
                                        <th class="url">URL</th>
                                        <th class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach ($MediaLibrary as $key => $Media)
                                        <?php
                                        $mediaPath = url(str_replace('http://localhost/', '/', $Media['original_url']));
                                        ?>
                                        <tr data-entry-id="{{ $i }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $Media['name'] }}</td>
                                            <td><img height="50" src="{{ $mediaPath }}"
                                                    alt="{{ $Media['file_name'] }}"></td>
                                            <td>{{ date('d M Y', strtotime($Media['created_at'])) }}</td>
                                            <td>
                                                <div class="mediaUrl">
                                                    <div class="path"></div><a href="javascript:void(0);"
                                                        onClick="copyToClipboard('{{ $mediaPath }}')"
                                                        class="copyMediaUrl">Copy
                                                        URL</a>
                                                </div>
                                            </td>
                                            <td>
                                                @if (isset($Media['id']))
                                                    <div class="actions actionBtnGroup">
                                                        <form
                                                            action="{{ route('admin.media-library.destroy', $Media['id']) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                            style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-xs btn-danger"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $i++ @endphp
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        function copyToClipboard(element) {
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(element).select();
            document.execCommand("copy");
            temp.remove();
            alert('Link Copied successfully.')
        }
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery(".datatable-mediaLibrary").dataTable({
                responsive: true
            });
            jQuery(document).on('submit', '#mediaLibrary', function() {
                e.preventDefault();
                var formData = new FormData(this);
                if (formData) {
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: '{{ route('admin.media-library.store') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            alert(response.message);
                            window.location.replace('/admin/hotels/');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        Dropzone.options.mediaLibraryDropzone = {
            url: '{{ route('admin.media-library.storeMedia') }}',
            //maxFilesize: 10, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            pleaceHolder: '.jpeg,.jpg,.png,.gif',
            maxFiles: 30,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form#mediaLibrary').find('input[name="media-library"]').remove()
                $('form#mediaLibrary').append('<input type="hidden" name="media-library[]" value="' + response
                    .name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form#mediaLibrary').find('input[name="media-library"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                if (message == 'You can not upload any more files.') {
                    message = 'You cannot upload more than 30 images'
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
