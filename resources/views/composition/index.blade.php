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

            <h1><a href="{{ route('composition.create') }}" class="btn btn-secondary"><strong>Add Composition</strong></a></h1>

            <table class="table table-bordered">
                <th class="text-center"> S.No. </th>
                <th class="text-center"> Zoodiac Sign Sun</th>
                <th class="text-center"> Zoodiac Sign Moon</th>
                <th class="text-center"> Zoodiac Sign Rising</th>
                <th class="text-center"> Audio </th>
                <th class="text-center"> Action </th>
                @forelse($data as $key => $audio)
                <tbody>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ $audio->zoodiac_sign_sun }}</td>
                    <td class="text-center">{{ $audio->zoodiac_sign_moon }}</td>
                    <td class="text-center">{{ $audio->zoodiac_sign_rising }}</td>
                    <td class="text-center">
                        <audio controls>
                            <source src="{{ asset('storage/composition') . '/'. $audio->composed_audio_path }}" type="audio/ogg">
                            <source src="{{ asset('storage/composition') . '/'. $audio->composed_audio_path }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('composition.destroy', $audio->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
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
