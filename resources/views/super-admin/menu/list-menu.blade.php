@extends('layouts.main-master')
@section('content')
<div class="row">
  
        <div class="card">
            <div class="card-body">

                <a href="{{ route('new-menu') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-3">
                        <i class="ri-add-line align-middle ms-2"></i>  Create New
                    </button>
                </a>
               
                <div class="col-12">
                    @if(session('success_message'))
                      <div class="alert alert-success alert-dismissible fade show success-notification" role="alert">
                        {{ session('success_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                    @endif
                    <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Label</th>
                            <th>Icon</th>
                            <th>Parent</th>
                            <th>Sort</th>
                            <th>Action</th>
                        </tr>
                        </thead>


                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{$menu->id}}</td>
                                    <td>{{$menu->label}}</td>
                                    <td> <i class="fa {{$menu->icon}}"></i></td>
                                    <td>{{$menu->parent}}</td>
                                    <td>{{$menu->sort}}</td>
                                    <td>
                                        <div class="editable-buttons">
                                            <a href="{{ route('edit-menu', ['id' => encrypt($menu->id)]) }}">
                                                <button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            </a>
                                            <button type="button" onclick="detele({{$menu->id}})" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="fa fa-trash-o"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="ajax-loading" style="display: none;">
                        <div style="display: flex; justify-content: center; position: relative; top: -102px; background: #f8f9fa;">
                                {{-- <div class="spinner-border text-primary m-1" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> --}}
                                <div class="spinner-grow text-primary m-1" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                        </div>
                    </div>

                </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('menu-list-script')
    <!-- Required datatable js -->
    <script src="{{asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    
    <!-- Responsive examples -->
    <script src="{{asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    {{-- <script src="{{asset('/assets/js/custom/listmenu-datatable.init.js')}}"></script> --}}

    <!-- Sweet Alerts js -->
    <script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Sweet alert init js-->
    <script src="{{asset('/assets/js/pages/sweet-alerts.init.js')}}"></script>

<script>
     $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    $(function(){
        $("#datatable").DataTable();
    });
    function getMenus(menus) {
        var t = $("#datatable").DataTable();
        t.clear().draw();
        $('#ajax-loading').css('display', 'block');

        if(menus){
            menus.forEach((menu, num) => {
                     $('#ajax-loading').css('display', 'none');
                    const tr = $(
                        `
                        <tr>
                                <td>${++num}</td>
                                <td>${menu.label}</td>
                                <td> <i class="fa ${menu.icon}"></i></td>
                                <td>${menu.parent}</td>
                                <td>${menu.sort}</td>
                                <td>
                                    <div class="editable-buttons">
                                        <a href="/menu/edit/${menu.encrypte_id}">
                                            <button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </a>
                                        <button type="button" onclick="detele(${menu.id})" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `
                    );

                    t.row.add(tr[0]).draw();
                });
        }else{
            $.ajax({
            url: "/super-admin/menu/ajax/all",
            type: "POST",
            dataType: "JSON",
            success: function (resp) {
                console.log(resp.menus);
                resp.menus.forEach((menu, num) => {
                     $('#ajax-loading').css('display', 'none');
                    const tr = $(
                        `
                        <tr>
                                <td>${++num}</td>
                                <td>${menu.label}</td>
                                <td> <i class="fa ${menu.icon}"></i></td>
                                <td>${menu.parent}</td>
                                <td>${menu.sort}</td>
                                <td>
                                    <div class="editable-buttons">
                                        <a href="/menu/edit/${menu.encrypte_id}">
                                            <button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </a>
                                        <button type="button" onclick="detele(${menu.id})" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `
                    );

                    t.row.add(tr[0]).draw();
                });
            },
        });
        }

      
    }
    function detele(s){
        Swal.fire({
                title: "Are you sure?",
                text: "You are deleting this Menu",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#1cbb8c",
                cancelButtonColor: "#f32f53",
                confirmButtonText: "Yes, delete it!",
            }).then(function (t) {
                if (t.value == true) {
                    $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: "/super-admin/menu/delete/"+s,
                        type: "POST",
                        dataType: "JSON",
                        success: function (resp) {
                            if(resp.status == "success"){
                                $('.rightbar-overlay').css('display', 'none');
                                Swal.fire("Deleted!", "Menu deleted successfully", "success");
                                getMenus(resp.menus);
                            }
                        }
                    });
                    
                }
            });
    }
</script>
<script>
    $(document).ready(function() {
    setTimeout(function() {
        $('.success-notification').fadeOut('slow');
    }, 3000); // 500 milliseconds = 0.5 seconds
});
</script>
    
    
@endsection

