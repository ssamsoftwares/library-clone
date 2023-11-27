@extends('layouts.main')

@push('page-title')
    <title>All Registration Request</title>
@endpush

@push('heading')
    {{ 'Registration Request Users' }}
@endpush

@section('content')
@push('style')
<style>
    .ri-eye-line:before {
        content: "\ec95";
        position: absolute;
        left: 13px;
        top: 5px;
    }

    a.btn.btn-primary.waves-effect.waves-light.view {
        width: 41px;
        height: 32px;
    }

    .action-btns.text-center {
        display: flex;
        gap: 10px;
    }

    .ri-pencil-line:before {
        content: "\ef8c";
        position: absolute;
        left: 13px;
        top: 5px;
    }

    a.btn.btn-info.waves-effect.waves-light.edit {
        width: 41px;
        height: 32px;
    }


    table.dataTable>tbody>tr.child ul.dtr-details>li {
        white-space: nowrap !important;
    }
</style>
@endpush

    <x-status-message />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('registrationRequest.index') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ 'Library Image' }}</th>
                                    <th>{{ 'Full Name' }}</th>
                                    <th>{{ 'Library Name' }}</th>
                                    <th>{{ 'Contact Number' }}</th>
                                    <th>{{ 'Library Address' }}</th>
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'Actions' }}</th>

                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($regiReq as $key => $data)
                                    <tr>
                                        <td>
                                            @if (!empty($data->image))
                                                <img src="{{ asset($data->image) }}" alt="studentImg" width="85">
                                            @else
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ799fyQRixe5xOmxYZc3kAy6wgXGO-GHpHSA&usqp=CAU"
                                                    alt="studentImg" width="85">
                                            @endif
                                        </td>
                                        <td>{{ $data->full_name }}</td>
                                        <td>{{ $data->library_name }}</td>
                                        <td>{{ $data->contact_number }}</td>
                                        <td>
                                            <?php
                                            $libraryAddress = $data->library_address;
                                            echo substr($libraryAddress, 0, 10) . '<br>' . substr($libraryAddress, 10);
                                            ?>
                                        </td>

                                        <td>
                                            @if ($data->status == 'pending')
                                                <a href="{{ route('registrationRequest.updateStatus', $data->id) }}"
                                                    class="btn btn-danger btn-sm">Pending</a>
                                            @else
                                                <a href="#"
                                                    class="btn btn-primary btn-sm">Approved</a>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('registrationRequest.edit', $data->id) }}"
                                                    class="btn btn-info waves-effect waves-light edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>

                                                <a href="{{ route('registrationRequest.destroy', $data->id) }}"
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
                    {{ $regiReq->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
