@extends('layouts.default')

@section('title', 'Create A New Short URL')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9">
            <div class="mt-3 mt-md-5">
                <a href="{{ route('short-urls.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-circle-left mr-1"></i>
                    Go Back
                </a>
            </div>
            <div class="card bg-light shadow-lg mt-3">
                <div class="card-header">
                    <div class="row d-flex justify-content-left">
                        <h2 class="h2 display-5 font-weight-bolder">
                            Add New URL
                        </h2>
                    </div>
                </div>
                <form method="POST" action="{{ route('short-urls.store') }}">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="url">URL:</label>
                            <input type="text" class="form-control"
                                id="url" name="url" value="{{ old('url') }}"
                                placeholder="Enter URL To Shorten It">
                            @error('url')
                                <small class="text-danger font-weight-bolder">
                                    <i class="fas fa-times-circle mr-1"></i> {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
