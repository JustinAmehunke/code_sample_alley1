<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>STAK V2.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="STAK V2.0" name="description" />
        <meta content="ShinQ Ghana" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}"  />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}">
        <!-- Sweet Alert-->
        <link href="{{asset('/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/toastr/build/toastr.min.css')}}">
       
        <!-- Select 2 css -->
        <link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
        <!-- jquery.vectormap css -->
        <link href="{{asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />


        <!-- DataTables -->
        <link href="{{asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{asset('/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />  

        <!-- Bootstrap Css -->
        <link href="{{asset('/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome -->
        <link href="{{asset('/assets/libs/font-awesome/css/font-awesome.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />

        <!-- App Css-->
        <link href="{{asset('/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

<style>
    .form-control:focus {
    border-color: #66afe9;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
}
</style>
    </head>

    <body data-topbar="dark">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ========== Header ========== -->
            @include('layouts.header')
            <!-- Header End -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('layouts.left-sidebar')
            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        @yield('content')
                      
                    </div>
                    
                </div>
                <!-- End Page-content -->
               
               
                <!-- ========== Footer ========== -->
                @include('layouts.footer')
                <!-- Footer End -->
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
         @include('layouts.right-sidebar')
        <!-- /Right-bar End -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay">
            <div  style="  position: fixed; top: 0; left: 0; width: 100%;height: 100%; display: flex;
            justify-content: center;
            align-items: center;">
                 <div style="text-align: center">
                    <div class="spinner-border text-primary m-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div style="color: white;" >
                        <span id="loading-msg"></span>
                    </div>
                 </div>
            </div>
        </div>
       

        <!-- JAVASCRIPT -->
        <script src="{{asset('/assets/libs/jquery/jquery.min.js')}}"></script>
        @yield('new-menu-script')
        <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
         <!-- Sweet Alerts js -->
         <script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

        <!-- toastr plugin -->
        <script src="{{asset('/assets/libs/toastr/build/toastr.min.js')}}"></script>
       <script>
        $(document).ready(function() {
            //disable datatable alert pop up error
            $.fn.dataTable.ext.errMode = 'none';
        });
       </script>

        <!-- PAGE Specific Scripts -->
        <!-- SperAdmin -->
        @yield('menu-list-script')
      
        @yield('edit-menu-script')

        @yield('email-templates-script');
        @yield('application-status-script');
        
        <!-- apexcharts -->
        <script src="{{asset('/assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        {{-- <script src="{{asset('/assets/js/pages/dashboard.init.js')}}"></script> --}}
        <!-- jquery.vectormap map -->
        {{-- <script src="{{asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js')}}"></script> --}}

        <!-- App js -->
        <script src="{{asset('/assets/js/app.js')}}"></script>

        <script>
            $(document).on('click', '.auth_role', function(e){
                e.preventDefault();
                
                let dep_id = $(this).data('dep_id');
                let des_id = $(this).data('des_id');
                let dep_name = $(this).data('dep_name');

                console.log(dep_id+'_'+des_id);

                swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to switch to "+ dep_name +" PROFILE!",
                    type: 'warning',
                    icon:"warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, take me there!'
                }).then(function(t) {
                    if(t.value) {
                        $.ajax({
                            url: "/admin/user/switch/profile",
                            type: 'POST',
                            data: {'des_id': des_id, 'dep_id': dep_id },
                            success: function(resp) {
                                if(resp.status == 'success'){
                                    toastr.success("Profile switch to "+ dep_name +" successfully.", "Success!");
                                    window.location.reload();
                                }else{
                                    toastr.error("Unable switch profile to "+dep_name, "Failed!");
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                                // Handle error
                                toastr.error("Unable switch profile to "+dep_name, "Failed!");
                            }
                        }); 
                    }
                });
            })
        </script>
    </body>

</html>