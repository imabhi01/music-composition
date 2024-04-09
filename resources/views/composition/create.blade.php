@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            
            @if (session('failed'))
                <div class="alert alert-danger">
                    {!! session('failed') !!}
                </div>
            @endif

            <form action="{{ route('composition.save') }}" method="POST" enctype="multipart/form-data" files="true">
                {{ csrf_field() }}
                <div class="row row-cols-3">
                    <div class="col">
                        <div class="card bg-white shadow-sm">
                            <h5 class="p-2 m-2"><strong>Sun</strong></h5>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="signs">Signs</label>
                                    <select name="zoodiac_sign_sun" id="sign_sun" class="form-control">
                                        <option value="" selected>Select Your Zoodiac Sign</option>
                                        @foreach($signs as $key => $sign)
                                            <option value="{{ $sign }}">{{ $sign }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card bg-white shadow-sm">
                            <h5 class="p-2 m-2"><strong>Moon</strong></h5>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="signs">Signs</label>
                                    <select name="zoodiac_sign_moon" id="sign_moon" class="form-control">
                                        <option value="" selected>Select Your Zoodiac Sign</option>
                                        @foreach($signs as $key => $sign)
                                            <option value="{{ $sign }}">{{ $sign }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card bg-white shadow-sm">
                            <h5 class="p-2 m-2"><strong>Rising</strong></h5>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="signs">Signs</label>
                                    <select name="zoodiac_sign_rising" id="sign_rising" class="form-control">
                                        <option value="" selected>Select Your Zoodiac Sign</option>
                                        @foreach($signs as $key => $sign)
                                            <option value="{{ $sign }}">{{ $sign }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection
