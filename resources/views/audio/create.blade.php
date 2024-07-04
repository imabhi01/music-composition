@extends('layouts.app')

@section('content')
<div class="container p-4 bg-white shadow-sm">

    <h3><strong>Add Audio</strong></h3>

    <div class="row justify-content-center">
        <div class="col-md-12">

            @if (count($errors))    
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('audio.save') }}" method="POST" enctype="multipart/form-data" files="true">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="signs">Zoodiac Signs</label>
                    <select name="zoodiac_sign" id="signs" class="form-control" required>
                        <option value="{{ old('zoodiac_sign') }}" selected>Select Your Zoodiac Sign</option>
                        @foreach($signs as $key => $sign)
                            <option value="{{ $sign }}" @if(old('zoodiac_sign') == $sign) selected @endif>{{ $sign }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" selected>Select the category</option>
                        @foreach($category as $key => $cat)
                            <option value="{{ $cat }}" @if(old('category') == $cat) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="audio_file">Select Audio File</label>
                    <input type="file" name="audio_file" id="audio_file" class="form-control" placeholder="Choose an audio file" required/>
                </div>

                <div class="form-group mt-2">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
