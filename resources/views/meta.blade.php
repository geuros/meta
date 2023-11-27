<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Meta Test 2</title>
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
        <link rel="stylesheet" href="/css/app.css" />
    </head>
    <body>
    <div class="container pt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Upload File</h5>
                    </div>
                    <div class="card-body">
                        <div id="upload-container" class="text-center">
                            <button id="file-button" class="btn btn-primary">Select File</button>
                        </div>
                        <div class="progress mt-4" style="height: 25px; display: none">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            let file = $('#file-button');
            let resumable = new Resumable({
                target: '{{ route('upload') }}',
                query:{_token:'{{ csrf_token() }}'},
                chunkSize: 10*1024*1024,
                headers: {
                    'Accept' : 'application/json'
                },
                testChunks: false,
                throttleProgressCallbacks: 1,
            });

            resumable.assignBrowse(file);

            resumable.on('fileAdded', function (file) {
                showProgress();
                resumable.upload();
            });

            resumable.on('fileProgress', function (file) {
                updateProgress(Math.floor(file.progress() * 100));
            });

            resumable.on('fileSuccess', function (file, response) {
                alert('File uploaded.')
            });

            resumable.on('fileError', function (file, response) {
                alert('File uploading error.')
            });

            let progress = $('.progress');
            function showProgress() {
                progress.find('.progress-bar').css('width', '0%');
                progress.find('.progress-bar').html('0%');
                progress.find('.progress-bar').removeClass('bg-success');
                progress.show();
            }

            function updateProgress(value) {
                progress.find('.progress-bar').css('width', `${value}%`)
                progress.find('.progress-bar').html(`${value}%`)
            }

            function hideProgress() {
                progress.hide();
            }
        })
    </script>
    </body>
</html>
