
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/assets/images/logo_small_1.png" alt="logo-sm" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="/assets/images/logo_small_1.png" alt="logo-dark" height="20">
                    </span>
                </a>

                <a href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="/assets/images/favicon.ico" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="/assets/images/logo_small_1.png" alt="logo-light" style="background-color: #fff; padding: 3px; padding-bottom: 5px;" height="35">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
            @php
                $user_id = auth()->user()?->id;
                $user = \App\Models\User::with(['department', 'designation'])->find($user_id);
            @endphp

          

        </div>

        <div class="d-flex">
           {{-- {{ $user->department?->department_name}} --}}
            {{--  {{ $user->designation?->designations}}
            {{ auth()->user()->tbl_departments_id}}
            {{ auth()->user()->tbl_designations_id}} --}}
            <div class="dropdown d-inline-block">
                {{ $user->department?->department_name}} 
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="noti-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            {{-- <div class="col-auto">
                                <a href="#!" class="small"> View All</a>
                            </div> --}}
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">

                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-1">Your report has been generated</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">The Documents report you initiated has been generated successfully</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        
                    </div>
                   
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="/assets/images/users/avatar-1.png"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">{{Auth::user()->firstname}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
               
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#"><i class="ri-refresh-line align-middle me-1"></i><span>Switch Profile</span></a>
                    <div style="height: 200px; overflow-y: auto;">
                        <!-- item-->
                        @php

                            $records = \App\Models\UserAssignedFunction::where('tbl_users_id', auth()->user()?->id)
                                                        ->where('deleted', 0)
                                                        ->get();
                        @endphp
                        @if ($records)
                            @foreach ($records as $record)
                                <a class="dropdown-item auth_role" data-dep_name="{{$record->tbl_departments->department_name}}" data-dep_id="{{base64_encode($record->tbl_departments_id)}}" data-des_id="{{base64_encode($record->tbl_designations_id)}}" href="#">
                                    <i class="ri-user-line align-middle me-1"></i>   
                                    {{$record->tbl_departments->department_name}} 
                                    {!!($record->tbl_departments_id == $user->tbl_departments_id)?'<span class="badge bg-success float-end mt-1">Active</span>':""!!}
                                </a>
                            @endforeach
                        @endif
                        {{-- <a class="dropdown-item d-block" href="#"><i class="ri-settings-2-line align-middle me-1"></i>Role 2</a> --}}
                    </div>
                    <div class="dropdown-divider"></div>
                   
                    <div>
                          <!-- Authentication -->
                    <form method="POST" id="logout" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout').submit()" href="{{ route('logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>                      
                    </form>
                    </div>
                </div>
            </div>

            {{-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="ri-settings-2-line"></i>
                </button>
            </div> --}}

        </div>
    </div>
</header>