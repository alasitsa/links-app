@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Original') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($links as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>{{ $link->original }}</td>
                            <td>{{ $link->slug }}</td>
                            <td><a href="{{ url("/" . $link->slug) }}">{{ url("/" . $link->slug) }}</a></td>
                            <td><a href="{{ route("patch", [ $link->id ]) }}">{{ __('Edit') }}</a></td>
                            <td><a href="{{ route("delete", [ $link->id ]) }}">{{ __('Delete') }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-md-0">
                <a href="{{ route('patch') }}">{{ __('Add') }}</a>
            </div>
        </div>
    </div>
@endsection
