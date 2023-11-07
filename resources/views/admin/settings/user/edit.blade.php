@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit User')}}</title>
@endpush

@push('heading')
{{ __('Edit User') }}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{route('users.update',$user->id)}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <h4 class="card-title mb-3">{{__('User Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="name" label="Name" :value="$user->name" />
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address" :value="$user->email" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="password" label="Passsword" type="password"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="confirm-password" label="Confirm Password" type="password"/>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-lg-3">
                         <x-form.select name="plan" label="Plan" id="plan" chooseFileComment="--Select Plan--"
                         :options="[
                             'free' => 'Freemium',
                             'paid' => 'Paid',
                         ]" :selected="$user->plan" />
                         </div>

                         <div class="col-lg-3">
                            <x-form.input name="add_student_limit" label="Create Student Limit"
                            :value="($user->plan === 'paid') ? 'unlimited' : $user->add_student_limit"  id="add_student_limit" />

                         </div>

                        <div class="col-lg-6">
                                <strong>Role:</strong>
                                <select name="roles" class="form-select">
                                    @foreach ($roles as $roleValue => $roleLabel)
                                        <option value="{{ $roleValue }}" {{ in_array($roleValue, $userRole) ? 'selected' : '' }}>
                                            {{ $roleLabel }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Update User')}}</button>
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
        var userAddStudentLimit = "{{ $user->add_student_limit }}";

        planSelect.on("change", function () {
            if (planSelect.val() === "paid") {
                addStudentLimitInput.prop("disabled", true).val("unlimited");
            } else {
                addStudentLimitInput.prop("disabled", false).val(userAddStudentLimit);
            }
        });
        planSelect.trigger("change");
    });
</script>

@endpush
