@extends('layouts.app')

@section('content')

    <h1>Employee data upload</h1>

    <form action="{{route('upload.post')}}" enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <label for="file">Json File :</label>
            <input type="file" class="form-control" id="email">
        </div>
        <button type="submit" class="btn btn-default btn-primary">Submit</button>
        {{ csrf_field() }}
    </form>
@endsection