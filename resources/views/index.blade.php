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

            @if (count($dataViewTypes))
                <div class="form-group">
                    <select class="form-control" name="dataViewType">
                            @foreach($dataViewTypes as $dataViewType)
                                <option value="{{$dataViewType}}">View by {{ $dataViewType }}</option>
                            @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="btn btn-default btn-primary">Submit</button>
            {{ csrf_field() }}
        </form>
    </div>

    <div class="container">

        <div class="col-md-6">
            <div class="form-group collapse" id="contentPreview">
                <h1>Preview</h1>
                <textarea name="fileContent"></textarea>
                <div class="alert alert-danger collapse" id="errorMessage"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group collapse" id="jsonView">
                <h1>Json Result</h1>
                <textarea></textarea>
            </div>

            <div class="form-group collapse" id="chart-container">
                <h1>Employee Chart</h1>
            </div>
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
                    $('#jsonView').hide();
                    $('#chart-container').hide();

                    $('#contentPreview > textarea').text(e.target.result);
                    $('#contentPreview').show();
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
                    success: function (response) {
                        if (response.status === 'success') {
                            showDataOnSuccess(response.result);
                        } else if (response.status === 'error') {
                            $('#errorMessage').text(response.message);
                            $('#errorMessage').show();
                        }
                    },
                    error: function (err) {
                        console.log('Failed to send the data to server');
                        console.log(err);
                    }
                });
            }));

            function showDataOnSuccess(result) {
                if (result.dataViewType === 'chart') {
                    createEmployeeChart(JSON.parse(result.data));

                    $('#jsonView').hide();
                    $('#jsonView > textarea').text('');
                    $('#chart-container').show();
                } else {
                    $('#chart-container').hide();
                    $('#jsonView > textarea').text(result.data);
                    $('#jsonView').show();
                }

            }

            var employeeChart;
            function createEmployeeChart(employeeData) {
                if (employeeChart) {
                    employeeChart.init({'data' : employeeData});
                } else {
                    employeeChart = $('#chart-container').orgchart({
                        'data': employeeData
                    });
                }
            }
        });
    </script>
@endsection