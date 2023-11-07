@extends('layouts.main')
@push('page-title')
    <title>{{ 'User - ' }} {{ $user->name }}</title>
@endpush

@push('heading')
    {{ 'User Detail' }}
@endpush

@push('heading-right')
@endpush

@section('content')

    {{-- User details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ 'User Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Name :</span>
                        <span>
                            {{ $user->name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Email :</span>
                        <span>
                            {{ $user->email }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Role :</span>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $v)
                                    <label class="badge bg-success">{{ $v }}</label>
                                @endforeach
                            @endif

                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Plan Status :</span>
                        <span>
                            {{ !empty($user->plan)? $user->plan : "paid"}}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Create Student Limit :</span>
                        <span>
                            {{!empty($user->add_student_limit) ? $user->add_student_limit : "unlimited"}}
                        </span>
                    </h5>
                </div>
            </div>
        </div>


    </div>

@endsection
