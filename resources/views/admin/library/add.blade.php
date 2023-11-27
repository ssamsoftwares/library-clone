@extends('layouts.main')

@push('page-title')
    <title>{{ __('Add Library') }}</title>
@endpush

@push('heading')
    {{ __('Add Library') }}
@endpush

@section('content')
    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('library.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="card-title mb-3">{{ __('Library Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-form.input name="library_name" label="Library Name" />
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('superadmin'))
                        <div class="row">
                            <div class="col-lg-12">
                                <x-form.select label="Libary Owner" chooseFileComment="--Select Owner--"
                                    name="admin_id" :options="$usersWithRole" />
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-7">
                                <label for="">Library Logo</label>
                                <input type="file" accept="image/*" name="logo" id="logo"
                                    onchange="loadFile(event)" class="form-control">
                            </div>

                            <div class="col-lg-5 mt-4">
                                <img id="output" src="" alt=""
                                    style="max-width: 50%; max-height: 100px;">
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <x-form.textarea name="description" label="Description" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.textarea name="address" label="Address" />
                            </div>

                        </div>

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Add Library') }}</button>
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
