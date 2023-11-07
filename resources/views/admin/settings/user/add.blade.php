@extends('layouts.main')

@push('page-title')
    <title>{{ __('Add User') }}</title>
@endpush

@push('heading')
    {{ __('Add User') }}
@endpush

@section('content')
    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="card-title mb-3">{{ __('User Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="name" label="Name" />
                            </div>
                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email Address" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="password" label="Passsword" type="password" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="confirm-password" label="Confirm Password" type="password" />
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('superadmin'))
                        <div class="row">
                            <div class="col-lg-3">
                                <x-form.select label="Plan" id="plan" chooseFileComment="--Select Plan--" name="plan"
                                    :options="['free' => 'Freemium', 'paid' => 'Paid']" />
                            </div>

                            <div class="col-lg-3">
                                <x-form.input name="add_student_limit" id="add_student_limit"  label="Create Student Limit" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.select label="Role" chooseFileComment="--Select Role--" name="roles"
                                    :options="$roles" />
                            </div>
                        </div>
                        @endif

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Add User') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

<script>
    $(document).ready(function () {
        var planSelect = $("#plan");
        var addStudentLimitInput = $("#add_student_limit");

        planSelect.on("change", function () {
            if (planSelect.val() === "paid") {
                addStudentLimitInput.prop("disabled", true).val("unlimited");
            }
            else {
                addStudentLimitInput.prop("disabled", false).val("");
            }
        });
        planSelect.trigger("change");
    });
</script>
@endpush
