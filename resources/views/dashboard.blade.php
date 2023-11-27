@extends('layouts.main')

@push('page-title')
    <title>{{'Dashboard'}}</title>
@endpush

@push('heading')
    {{ 'Dashboard'}}
@endpush

@section('content')


{{-- quick info --}}
<div class="row">
    <x-design.card heading="Total Student" value="{{$total['student']}}" icon="mdi-account-convert" desc="Student"/>

    @if (auth()->user()->hasrole('superadmin'))
    <x-design.card heading="Total Owner" value="{{$total['librariesOwner']}}"  desc="Libraries Owner"/>
    <x-design.card heading="Total Library" value="{{$total['librariesApproved']}}" icon="mdi mdi-library"  desc="libraries Approved"/>
    <x-design.card heading="Total Library" value="{{$total['librariesPending']}}" icon="mdi mdi-book"  desc="libraries Pending"/>
    <x-design.card heading="Total Library" value="{{$total['libraries']}}" icon="mdi mdi-library"  desc="libraries"/>
    @endif
</div>

<h4 class="card-title mt-4 mb-4">{{__('Plan Expired Students within 5 days')}}</h4>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="justify-content-end d-flex">
            <x-search.table-search action="{{ route('dashboard') }}" method="get" name="search"  value="{{isset($_REQUEST['search'])?$_REQUEST['search']:''}}"
            btnClass="search_btn"/>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ 'Plan' }}</th>
                            <th>{{ 'Student Name' }}</th>
                            <th>{{ 'Student Email' }}</th>
                            <th>{{ 'Valid From Date ' }}</th>
                            <th>{{ 'Valid Upto Date' }}</th>
                            {{-- @if (auth()->user()->hasrole('superadmin') || auth()->user()->hasrole('admin')) --}}
                            @if (auth()->user()->hasrole('superadmin'))
                            <th>{{ 'Actions' }}</th>
                            @endif
                            <th>{{ 'Mode of Payment' }}</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($plans as $p)
                        <tr>
                            <td>{{ $p->plan }}</td>
                            <td>{{ $p->student->name }}</td>
                            <td>{{ $p->student->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->valid_from_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->valid_upto_date)->format('d-m-Y') }}</td>

                            {{-- @if (auth()->user()->hasrole('superadmin') || auth()->user()->hasrole('admin')) --}}

                            @if (auth()->user()->hasrole('superadmin'))
                            <td>
                                <div class="action-btns text-center" role="group">
                                    <a href="{{ route('plan.edit',['plan'=> $p->id ]) }}" class="btn btn-info waves-effect waves-light edit btn-sm w-100 p-lg-2 m-lg-2">
                                       Update Plan
                                    </a>

                                </div>
                            </td>
                            @endif

                            <td>{{ $p->mode_of_payment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $plans->onEachSide(5)->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>

@endsection
