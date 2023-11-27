@extends('layouts.main')

@push('page-title')
    <title>{{'Blank page'}}</title>
@endpush

@push('heading')
    {{ 'Blank page'}}
@endpush

@section('content')

<div class='row'>
    <div class="col align-items-center justify-content-center">
        <h2>
            Blank Page
        </h2>
    </div>
</div>



@endsection
