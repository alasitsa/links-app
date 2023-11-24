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
                        <th>{{ __('Author') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($links as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>{{ $link->original }}</td>
                            <td>{{ $link->slug }}</td>
                            <td>{{ $link->user->email }}</td>
                            <td><a href="{{ url("/" . $link->slug) }}">{{ url("/" . $link->slug) }}</a></td>
                            <td><a href="{{ route("admin-patch", [ $link->id ]) }}">{{ __('Edit') }}</a></td>
                            <td><a href="{{ route("admin-delete", [ $link->id ]) }}">{{ __('Delete') }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
