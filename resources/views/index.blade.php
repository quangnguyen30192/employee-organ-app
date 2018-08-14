@extends('layouts.app')

@section('style')
    <style>
        #jsonTextView {
            font-size: 1.6rem;
            font-family: Monaco;
        }

        .container {
            margin: 2em;
        }
    </style>
@endsection

@section('content')

    <h1>Employee data upload</h1>

    <div class="container">
        <form id="form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Json File :</label>
                <input type="file" class="form-control" name="file" required>
            </div>
            <button type="submit" class="btn btn-default btn-primary">Submit</button>
            {{ csrf_field() }}
        </form>
    </div>

    <div class="container">
        <div class="alert alert-danger collapse" id="errorMessage"></div>
        <textarea class="form-control collapse" name="" id="jsonTextView" rows="20" cols="30"></textarea>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function (e) {

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
                            $('#jsonTextView').show();
                            $('#jsonTextView').text(JSON.stringify(result.data, undefined, 4));
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