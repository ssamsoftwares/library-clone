@extends('layouts.main')

@push('page-title')
    <title>{{ __('Edit Registration Request') }}</title>
@endpush

@push('heading')
    {{ __('Edit Registration Request - ') }} {{ $registrationReq->full_name }}
@endpush

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        input[switch]+label:after {

            left: -22px;
            margin-left: 25px;

        }

        input[switch]+label {
            width: 80px !important;
        }
    </style>
@endpush

@section('content')
    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <a href="javascript:history.back()" class="btn btn-warning mb-2">Back</a>
                    <form method="post" action="{{ route('registrationRequest.update', $registrationReq->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $registrationReq->id }}">
                        <h4 class="card-title mb-3">{{ __('Registration Request Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="full_name" label="Full Name" :value="$registrationReq->full_name" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email Address" :value="$registrationReq->email" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="contact_number" label="Contact Number" :value="$registrationReq->contact_number" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="library_name" label="Library Name" :value="$registrationReq->library_name" />
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-8">
                                <label for="logo">Library Logo</label>
                                <input type="file" accept="image/*" name="image" id="logo" class="form-control"
                                    onchange="loadFile(event)">
                            </div>
                            <div class="col-lg-4 mt-lg-2">
                                @if (!empty($registrationReq->image))
                                    <img src="{{ asset($registrationReq->image) }}" id="output" alt="logo" width="50"
                                        height="50">
                                @else
                                    <img src="" id="output" alt="logo" width="50" height="50">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-form.textarea name="library_address" label="Library Address" :value="$registrationReq->library_address" />
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <label for="status">Status</label>
                                <div class="status switch-container">
                                    <input type="checkbox" id="status" name="status" switch="bool" value="approved"
                                        {{ $registrationReq->status === 'approved' ? 'checked' : '' }} style="width:100px;">
                                    <label for="status" data-on-label="approved" class ="hihi"
                                        data-off-label="pending"></label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Update Library') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('registrationRequest.asignPasswordUser', $registrationReq->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $registrationReq->id }}">
                        <h4 class="card-title mb-3 mt-4">{{ __('Asign the password') }}</h4>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <x-form.input name="password" label="Passsword" type="password"/>
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="confirm-password" label="Confirm Password" type="password"/>
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Update Password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
        };
    </script>
@endpush
