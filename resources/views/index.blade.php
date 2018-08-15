@extends('layouts.app')

@section('style')
    <style>
        textarea {
            font-size: 1.6rem;
            font-family: Monaco;
            height: 30em;
            width: 100%;
        }

        .container {
            margin: 2em;
        }
    </style>
@endsection

@section('content')

    <h1>Employee data upload</h1>

    <div class="container">
        <form id="form" enctype="multipart/form-data" class="form-inline">
            <div class="form-group">
                <label for="file">Json File :</label>
                <input type="file" class="form-control" id="upload-file" name="file" required>
            </div>
            <button type="submit" class="btn btn-default btn-primary">Submit</button>
            {{ csrf_field() }}
        </form>
    </div>

    <div class="container">
        <div class="form-group collapse" id="jsonViewBefore">
            <h4>Preview</h4>
            <textarea name="fileContent"></textarea>
            <div class="alert alert-danger collapse" id="errorMessage"></div>
        </div>


        <div class="form-group collapse" id="jsonViewAfter">
            <h4>Result</h4>
            <textarea></textarea>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function (e) {

            $("#upload-file").change(function () {
                var file = document.getElementById('upload-file').files[0];
                var reader = new FileReader();
                reader.readAsText(file);
                reader.onload = function (e) {
                    $('#errorMessage').hide();
                    $('#jsonViewAfter').hide();

                    $('#jsonViewBefore > textarea').text(e.target.result);
                    $('#jsonViewBefore').show();
                };
            });

            $("#form").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('upload')}}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if (result.status === 'success') {
                            $('#jsonViewAfter').show();
                            $('#jsonViewAfter > textarea').text(JSON.stringify(result.data, undefined, 4));
                        } else if (result.status === 'error') {
                            $('#errorMessage').show();
                            $('#errorMessage').text(result.message);
                        }
                    },
                    error: function (err) {
                        console.log('Failed to send the data to server');
                        console.log(err);
                    }
                });
            }));
        });
    </script>
@endsection