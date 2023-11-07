@extends('layouts.main')

@push('page-title')
    <title>{{'plan purchase'}}</title>
@endpush

@push('heading')
    {{ 'plan purchase'}}
@endpush

@section('content')

<div class='row'>
    <div class="col align-items-center justify-content-center">
        <h2>
            Please purchase the paid plan
        </h2>
        <a href="#" class="btn btn-success">Purchase</a>
    </div>
</div>



@endsection
