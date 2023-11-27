@extends('layouts.main')

@push('page-title')
    <title>{{ __('Edit Library') }}</title>
@endpush

@push('heading')
    {{ __('Edit Library - ') }} {{ $library->library_name }}
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

                    <form method="post" action="{{ route('library.update', $library->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $library->id }}">
                        <h4 class="card-title mb-3">{{ __('Library Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-form.input name="library_name" label="Library Name" :value="$library->library_name" />
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('superadmin'))
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    <x-form.select label="Role" chooseFileComment="--Select Role--" name="admin_id"
                                        :options="$usersWithRole" :selected="$library->admin_id" />
                                </div>
                            </div>
                        @elseif (auth()->user()->hasRole('admin'))
                            @if ($library->status == 'approved')
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <label for="managers"> Asign the Library Select The Managers</label>
                                        <select name="manager_id[]" id="managers"
                                            class="form-control managers-multiple" multiple="multiple">
                                            <option value="" disabled>Select Managers</option>
                                            @foreach ($managers as $key => $manager)
                                                <option value="{{ $manager->id }}"
                                                    {{ is_array(json_decode($library->manager_id)) && in_array($manager->id, json_decode($library->manager_id)) ? 'selected' : '' }}>
                                                    {{ $manager->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if (auth()->user()->hasRole('admin') ||
                                            auth()->user()->hasRole('manager'))
                                        <div class="col-lg-12 mt-4">
                                            <label for="students">Add this Library Select Students</label>
                                            <select name="student_id[]" id="students" class="form-control student-multiple"  multiple="multiple">
                                                <option value="" disabled>Select Student</option>
                                                @foreach ($students as $key => $student)
                                                    <option value="{{ $student->id }}">
                                                        {{ $student->name }}
                                                    </option>

                                                    {{-- <option value="{{ $student->id }}" @if(($student->id ==$library->id)) selected @endif>
                                                        {{ $student->name }}
                                                    </option> --}}

                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                </div>
                            @endif
                        @endif

                        <div class="row mt-4">
                            <div class="col-lg-8">
                                <label for="logo">Library Logo</label>
                                <input type="file" accept="image/*" name="logo" id="logo" class="form-control"
                                    onchange="loadFile(event)">
                            </div>
                            <div class="col-lg-4 mt-lg-4">
                                @if (!empty($library->logo))
                                    <img src="{{ asset($library->logo) }}" id="output" alt="" width="50"
                                        height="50">
                                @else
                                    <img src="" id="output" alt="logo" width="50" height="50">
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <x-form.textarea name="description" label="Description" :value="$library->description" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.textarea name="address" label="Address" :value="$library->address" />
                            </div>
                        </div>

                        @hasrole('superadmin')
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    <label for="status">Status</label>
                                    <div class="status switch-container">
                                        <input type="checkbox" id="status" name="status" switch="bool" value="approved"
                                            {{ $library->status === 'approved' ? 'checked' : '' }} style="width:100px;">
                                        <label for="status" data-on-label="approved" data-off-label="pending"></label>
                                    </div>
                                </div>
                            </div>
                        @endhasrole

                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <x-form.radio label="Active Status" name="active_status" id="active_status"
                                    :value="$library->active_status" />
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

@endsection
@push('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.managers-multiple').select2();
        $('.student-multiple').select2();
    </script>
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
