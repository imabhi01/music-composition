@extends('layouts.app')

@section('content')
<div class="container p-4 bg-white shadow-sm">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {!! session('success') !!}
                </div>
            @endif

            <h1><a href="{{ route('audio.create') }}" class="btn btn-secondary"><strong>Add Audio</strong></a></h1>

            <table class="table table-bordered">
                <th class="text-center"> S.No. </th>
                <th class="text-center"> Zoodiac Sign </th>
                <th class="text-center"> Category </th>
                <th class="text-center"> File Name </th>
                <th class="text-center"> Audio </th>
                <th class="text-center"> Action </th>
                @forelse($data as $key => $audio)
                <tbody>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ $audio->zoodiac_sign }}</td>
                    <td class="text-center">{{ $audio->category }}</td>
                    <td class="text-center">{{ $audio->upload->file_name }}</td>
                    <td class="text-center">
                        <audio controls>
                            <source src="{{ asset('storage') . '/'. $audio->upload->file_path }}" type="audio/ogg">
                            <source src="{{ asset('storage') . '/'. $audio->upload->file_path }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                    <td class="text-center">
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('audio.edit', $audio->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            </div>
                            
                            <div class="col">
                                <form action="{{ route('audio.destroy', $audio->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                        
                       
                    </td>
                </tbody>
                @empty
                    <tbody>
                        <td colspan='6' class="text-center"><strong>No Data</strong></td>
                    </tbody>
                @endforelse
            </table>

        </div>
    </div>
</div>
@endsection
