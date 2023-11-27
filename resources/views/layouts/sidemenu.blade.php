<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!-- User details -->
        <div class="user-profile text-center mt-3">
            @auth
                @if (auth()->user()->hasAnyRole(['superadmin', 'admin', 'manager']))
                    <div class="mt-3">
                        <h4 class="font-size-16 mb-1">Hello {{ ucfirst(request()->user()->name) }}</h4>
                        <span class="text-muted">
                            <i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                            {{ ucfirst(request()->user()->role) }}
                        </span>
                    </div>
                @elseif (session()->has('student_name'))
                    <div class="mt-3">
                        <h4 class="font-size-16 mb-1">Hello {{ ucfirst(session('student_name')) }}</h4>
                        <span class="text-muted">

                        </span>
                    </div>
                @endif
            @endauth
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @auth
                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="ri-vip-crown-2-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @can('user-list')
                        @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <li class="menu-title">
                                @if (auth()->user()->hasRole('superadmin'))
                                    Libraries Owner
                                @else
                                    Managers
                                @endif
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-account-circle-line"></i>
                                    <span>
                                        @if (auth()->user()->hasRole('superadmin'))
                                            Libraries Owner
                                        @else
                                            Managers
                                        @endif
                                    </span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('users.index') }}">View All</a></li>
                                    <li><a href="{{ route('users.create') }}">Add New</a></li>
                                </ul>
                            </li>
                        @endif
                    @endcan

                    {{-- Library --}}
                    @if (auth()->user()->hasAnyRole(['superadmin', 'admin']) && auth()->user()->register_type == 'internal')
                        <li class="menu-title">Library</li>
                        <li class="">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-book-open-fill"></i>
                                <span>Libraries</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="">
                                    <a href="{{ route('libraries') }}">Library</a>
                                </li>
                                <li><a href="{{ route('library.add') }}">Add Library</a></li>
                            </ul>
                        </li>
                    @endif

                    <li class="menu-title">Student</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-folder-user-fill"></i>
                            <span>Students</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('students') }}">View All</a></li>
                            <li><a href="{{ route('student.add') }}">Add New</a></li>
                            @can('plan-list')
                                <li><a href="{{ route('plans') }}">Assign Plan</a></li>
                            @endcan
                        </ul>
                    </li>

                    @role('superadmin')
                        <li class="menu-title">Registration Request</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-group-2-fill"></i>
                                <span>Registration Request</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('registrationRequest.index') }}">View All</a></li>
                            </ul>
                        </li>

                        <li class="menu-title">Settings</li>
                        <li class="">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-mail-settings-fill"></i>
                                <span>Settings</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="{{ request()->is('roles/*') ? 'active' : '' }}">
                                    <a href="{{ route('roles.index') }}">Role</a>
                                </li>
                            </ul>
                        </li>
                    @endrole
                @endauth

                {{-- STUDENT SIDEBAR START --}}
                @guest
                    @if (session('student_name'))
                        <li>
                            <a href="{{ route('student.dashboard') }}" class="waves-effect">
                                <i class="ri-vip-crown-2-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-title">Profile</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Profile</span>
                            </a>

                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('student.profile') }}">My Profile</a></li>
                                <li><a href="{{ route('student.planDetails') }}">Plan Details</a></li>
                            </ul>

                        </li>

                        <li>
                            <a href="{{ route('student.logout') }}"
                                onclick="return confirm('Are you sure logout this site!')" class="waves-effect">
                                <i class="ri-pencil-ruler-2-line"></i>
                                <span class="text-danger">Logout</span>
                            </a>
                        </li>
                    @endif
                @endguest
                {{-- STUDENT SIDEBAR END --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
