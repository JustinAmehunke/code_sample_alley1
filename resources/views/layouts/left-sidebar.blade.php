<style>
    #sidebar-menu ul li a {
        font-size: 12.3px;
    }
</style>
{{-- In local env: change ->new_link to ->link --}}
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!-- User details -->
        {{-- <div class="user-profile text-center mt-3">
            <div class="">
                <img src="/assets/images/users/avatar-1.jpg" alt="" class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1" style="color: #fffffff2;">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</h4>
                <span class="text-muted" style="color: #b0b7c5"><i class="ri-record-circle-line align-middle font-size-14 text-success" ></i> Online</span>
            </div>
        </div> --}}

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menus</li>
                @php
                   $parentMenu = App\Models\Menu::getSidebarParentMenus();
                //    $parentMenu = App\Http\Traits\Menus::getParentMenus();
                   
                @endphp
                @foreach ($parentMenu as $parent)
                    {{-- <li class="menu-title">{{$parent->label}}</li> --}}
                    <li>
                        @php
                            $childrenMenu = App\Models\Menu::getSidebarChildrenMenus($parent->id);
                        @endphp
                        <a href="{{count($childrenMenu) ? 'javascript: void(0);' : $parent->new_link}}" class=" {{count($childrenMenu)?'has-arrow':''}} waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>{{$parent->label}} </span> {{--  - {{$parent->id}} --}}
                        </a>
                       
                        @if ($childrenMenu !== null)
                            <ul class="sub-menu" aria-expanded="false">
                                @foreach ($childrenMenu as $child)
                                    <li>
                                        @php
                                            $subChildrenMenu = App\Models\Menu::getSidebarSubChildrenMenus($child->id);
                                        @endphp
                                        <a href="{{count($subChildrenMenu) ? 'javascript: void(0);' : $child->new_link}}" class="{{count($subChildrenMenu)?'has-arrow':''}}"> 
                                        <i class="ri-layout-3-line"></i>{{$child->label}} </a> {{--  -{{$child->id}} --}}
                                       
                                        @if ($subChildrenMenu !== null)
                                            <ul class="sub-menu" aria-expanded="false">
                                                @foreach ($subChildrenMenu as $subchild)
                                                    <li>
                                                        @php
                                                            $subSubChildrenMenu = App\Models\Menu::getSidebarSubSubChildrenMenus($subchild->id);
                                                        @endphp
                                                        <a href="{{count($subSubChildrenMenu) ? 'javascript: void(0);' : $subchild->new_link}}" class="{{count($subSubChildrenMenu)?'has-arrow':''}}"> 
                                                        <i class="ri-profile-line"></i>{{$subchild->label}}</a>
                                                        @if ($subSubChildrenMenu)
                                                            <ul class="sub-menu" aria-expanded="false">
                                                                @foreach ($subSubChildrenMenu as $subsubchild)
                                                                    <li><a href="{{$subsubchild->new_link}}">{{$subsubchild->label}}</a></li> {{--  -{{$subsubchild->id}} --}}
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        
                    </li>

                @endforeach
                
            </ul>
        </div>
        <!-- Sidebar -->

       

    </div>
</div>