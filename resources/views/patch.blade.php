@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Patch') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ $link ? route('patch', [$link->id]) : route('patch') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="original" class="col-md-4 col-form-label text-md-end">{{ __('Original') }}</label>

                                <div class="col-md-6">
                                    <input id="original" type="text" class="form-control @error('original') is-invalid @enderror" name="original" @if($link) placeholder="{{ $link->original }}" @endif autocomplete="original" autofocus>

                                    @error('original')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="slug" class="col-md-4 col-form-label text-md-end">{{ __('Slug') }}</label>

                                <div class="col-md-6">
                                    <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" @if($link) placeholder="{{ $link->slug }}" @endif autocomplete="slug" autofocus>

                                    @error('slug')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Patch') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
