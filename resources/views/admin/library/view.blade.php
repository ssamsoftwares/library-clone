@extends('layouts.main')
@push('page-title')
    <title>{{ 'User Details - ' }} {{ $library->library_name }}</title>
@endpush

@push('heading')
    {{ 'User Details' }}
@endpush

@push('heading-right')
@endpush

@section('content')
    {{-- User details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ ' Library Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span> Library Logo :</span>
                        <span>
                            @if (!empty($library->logo))
                                <img src="{{ asset($library->logo) }}" alt="Library Logo" width="85"
                                    class="rounded-circle img-thumbnail">
                            @else
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ799fyQRixe5xOmxYZc3kAy6wgXGO-GHpHSA&usqp=CAU"
                                    alt="Library Logo" width="85">
                            @endif
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Library Name :</span>
                        <span>
                            {{ $library->library_name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>address :</span>
                        <span>
                            {{ $library->address }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>description :</span>
                        <span>
                            {{ $library->description }}
                        </span>
                    </h5>
                    <hr>

                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ ' Library Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Created By :</span>
                        <span>
                            {{ $library->creator->name }}
                        </span>
                    </h5>
                    <hr>
                    <h5 class="card-title">
                        <span>Asign By :</span>
                        <span>
                            {{ $library->admin->name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Status :</span>
                        <span>
                            {{ !empty($library->status) ? Str::ucfirst($library->status) : null }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Active Status :</span>
                        <span>
                            {{ !empty($library->active_status) ? Str::ucfirst($library->active_status) : null }}
                        </span>
                    </h5>
                    <hr>

                </div>
            </div>
        </div>


    </div>

    <x-status-message />
    {{-- SHOW Students --}}
    <div class="row">

        <h5>{{ 'Show User' }}</h5>
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('library.libraryStudentView') }}" method="get"
                        name="search" value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}"
                        btnClass="search_btn" />
                </div>

                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Email' }}</th>
                                    <th>{{ 'Plan Status' }}</th>
                                    <th>{{ 'Created By' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @if ($admin)
                                    <tr>
                                        <td>{{ optional($admin)->name }}</td>
                                        <td>{{ optional($admin)->email }}</td>
                                        <td>{{ !empty(optional($admin)->plan) ? optional($admin)->plan : 'paid' }}</td>
                                        <td>
                                            {{ !empty(optional($admin->createdByUser)->name) ? optional($admin->createdByUser)->name : 'N/A' }}
                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{ route('library.libraryStudentView', ['manager_id' => 0, 'admin_id' => optional($admin)->id, 'lib_id' => $library->id]) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($managers as $key => $manager)
                                    <tr>
                                        <td>{{ $manager->name }}</td>
                                        <td>{{ $manager->email }}</td>


                                        <td>{{ !empty($manager->plan) ? $manager->plan : 'paid' }}</td>
                                        <td>
                                            {{ !empty($manager->createdByUser->name) ? $manager->createdByUser->name : null }}
                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('library.libraryStudentView', ['admin_id' => 0, 'manager_id' => $manager->id, 'lib_id' => $library->id]) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                {{-- <a href="{{ route('users.edit',$manager->id) }}"
                                                class="btn btn-info waves-effect waves-light edit">
                                                <i class="ri-pencil-line"></i>
                                            </a> --}}


                                            <a href="{{ route('library.managerRemove', ['manager_id' => $manager->id, 'lib_id' => $library->id]) }}" class="btn btn-danger waves-effect waves-light del"
                                            onclick="return confirm('Are you sure you want to remove this manager?')"> <i class="ri-delete-bin-line"></i></a>

                                                {{-- <form method="POST"
                                                    action="{{ route('library.managerRemove', ['manager_id' => $manager->id, 'lib_id' => $library->id]) }}"
                                                    style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger waves-effect waves-light del"
                                                        onclick="return confirm('Are you sure you want to remove this manager?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($managers->isNotEmpty())
                        {{ $managers->onEachSide(5)->links() }}
                    @else
                        {{-- <p>{{ $message }}</p> --}}
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
