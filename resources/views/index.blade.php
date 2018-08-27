@extends('layouts.app')
@section('content')

    <h1>Employee data upload</h1>

    <div class="container">
        <form id="form" enctype="multipart/form-data" class="form-inline" upload-url="{{route('upload')}}">
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
        </form>
    </div>

    <div class="container-fluid">
        <div class="col-md-4">
            <div class="form-group collapse" id="contentPreview">
                <h1>Preview</h1>
                <textarea name="fileContent" readonly></textarea>
                <div class="alert alert-danger collapse" id="errorMessage"></div>
            </div>
        </div>

        <div class="col-md-8">
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