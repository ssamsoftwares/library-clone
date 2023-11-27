<?php
use Carbon\Carbon;
use App\Models\RegistrationRequest;
use App\Models\UserReqNotification;

$regReqUser = RegistrationRequest::latest()->take(2)->get();
$notiCount = UserReqNotification::where(['is_seen'=>"unseen"])->count();

?>
{{-- @if($show == 'true' && count($notifications) > 0) --}}
@if($show == 'true')
    <!-- notification section -->
    <div class="dropdown d-inline-block">
        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ri-notification-3-line"></i>
            <span class="noti-dot"></span>
            <span class="">{{$notiCount}}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
            aria-labelledby="page-header-notifications-dropdown">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0"> Notifications </h6>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('registrationRequest.index')}}" class="small"> View All</a>
                    </div>
                </div>
            </div>
            <div data-simplebar style="max-height: 230px;">
                @forelse ($regReqUser as $r )
                <a href="{{route('registrationRequest.edit',$r->id)}}" class="text-reset notification-item">
                    <div class="d-flex">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                {{-- <i class="ri-shopping-cart-line"></i> --}}
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQm4fj2Ao4Av0FzJN6c20gCFuTNfFwyiO4pfQ&usqp=CAU" alt="" width="20px">
                            </span>
                        </div>
                        <div class="flex-1">
                            <h6 class="mb-1"><span class="text-info">Name</span> :{{$r->full_name}}</h6>
                            <h6 class="mb-1"><span class="text-info">Library Name</span> :{{$r->library_name}}</h6>
                            <div class="font-size-12 text-muted">
                                <p class="mb-1">Registration Request User</p>
                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{Carbon::parse($r->created_at)->diffForHumans()}} </p>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <p class="mb-1 text-center">Not Found Notification</p>
                @endforelse
            </div>

            <div class="p-2 border-top">
                <div class="d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center" href="{{route('registrationRequest.index')}}">
                        <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
