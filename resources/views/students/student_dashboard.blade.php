@extends('layouts.main')

@push('page-title')
    <title>{{'Dashboard'}}</title>
@endpush

@push('heading')
    {{ 'Hello'}}  {{ Str::ucfirst($checkStudent->name) }}
@endpush

@section('content')

<h4 class="card-title mt-4 mb-4">{{__('Your Plan Expired within 5 days')}}</h4>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ 'Plan' }}</th>
                            <th>{{ 'Student Name' }}</th>
                            <th>{{ 'Student Email' }}</th>
                            <th>{{ 'Valid From Date ' }}</th>
                            <th>{{ 'Valid Upto Date' }}</th>
                            <th>{{ 'Mode of Payment' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($plans as $p)
                        <tr>
                            <td>{{ $p->plan }}</td>
                            <td>{{ $p->student->name }}</td>
                            <td>{{ $p->student->email }}</td>
                            <td>{{ $p->valid_from_date }}</td>
                            <td>{{ $p->valid_upto_date }}</td>
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
