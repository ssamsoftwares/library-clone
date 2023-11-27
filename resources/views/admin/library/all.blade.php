@extends('layouts.main')

@push('page-title')
    <title>All libraries</title>
@endpush

@push('heading')
    {{ 'libraries' }}
@endpush

@section('content')
    @push('style')
    
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('libraries') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>{{ 'library Logo' }}</th>
                                    <th>{{ 'library Name' }}</th>
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'Created By' }}</th>
                                    <th>{{ 'Assign By' }}</th>
                                    <th>{{ 'Actions' }}</th>

                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($library as $key => $li)
                                    <tr>
                                        <td>
                                            @if (!empty($li->logo))
                                                <img src="{{ asset($li->logo) }}" alt="studentImg" width="85">
                                            @else
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ799fyQRixe5xOmxYZc3kAy6wgXGO-GHpHSA&usqp=CAU"
                                                    alt="studentImg" width="85">
                                            @endif
                                        </td>
                                        <td>{{ $li->library_name }}</td>

                                        {{-- <td>{{ $li->status }}</td> --}}
                                        <td>
                                            @if ($li->status == 'pending')
                                                <a href="{{ route('library.libraryUpdateStatus', $li->id) }}"
                                                    class="btn btn-danger btn-sm">Pending</a>
                                            @else
                                                <a href="#" class="btn btn-primary btn-sm">Approved</a>
                                            @endif

                                        </td>

                                        <td>{{ !empty($li->creator->name) ? $li->creator->name : '' }}</td>
                                        <td>{{ !empty($li->admin->name) ? $li->admin->name : '' }}</td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('library.show', $li->id) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="{{ route('library.edit', $li->id) }}"
                                                    class="btn btn-info waves-effect waves-light edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>

                                                <a href="{{ route('library.destroy', $li->id) }}"
                                                    class="btn btn-danger waves-effect waves-light del"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $library->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
