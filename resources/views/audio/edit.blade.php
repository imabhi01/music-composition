@extends('layouts.app')

@section('content')
<div class="container p-4 bg-white shadow-sm">

    <h3><strong>Edit Audio</strong></h3>

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

            <form action="{{ route('audio.update', $audio->id) }}" method="POST" enctype="multipart/form-data" files="true">
                {{ csrf_field() }}
                @method('PUT')

                @include('audio._form')
                
            </form>
        </div>
    </div>
</div>
@endsection
