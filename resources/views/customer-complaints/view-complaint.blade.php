
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>View Complaint</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="View Request" name="description" />
        <meta content="ShrinQ" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}"  />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

        <style>
            .bg-color-white{
                background-color: white !important;
            }
            .oml-btn {
                padding: 8px 20px;
                font-size: 15px;
                position: relative !important;
                font-family: 'Montserrat', sans-serif;
                cursor: pointer;
                font-weight: 500;
                line-height: 1.2;
                transition: all 0.3s ease 0s;
                border-radius: 23px;
            }
            .oml-btn-success {
                border: 0;
                background-color: #049774;
                color: #fff;
                background: linear-gradient(270deg, #60B848 1.64%, #009677 98.36%);
            }
            .oml-btn-success:hover {
                color: #fff;
                background-image: linear-gradient(to left, #049774, #44a858 22%, #7db840);
            }

            .oml-btn-success:hover {
                color: #fff;
                background-color: #3bb347;
                border-color: #38a943;
            }
            .request-header{
                color: #ffff;
                background-image: url('/assets/images/old-mutual-header.png')
            }
            .bg-overlay{
                color: #ffff;
                background-image: url('/assets/images/form_background.jpg');
                background-color: #fff !important;
                background-size: contain;
                background-repeat: no-repeat;
                /* background-size: 15px 15px; */
            }
            .card-shadow{
                margin-bottom: 24px;
                -webkit-box-shadow: 1px 0 20px rgba(0,0,0,.05);
                box-shadow: 1px 0 20px rgba(0,0,0,.05);
            }
        </style>
    </head>

    <body class="bg-color-white">
        <div class="bg-overlay"></div>
        <span class="logo-lg" style=" margin-top: 200px;
        position: absolute;">
            <img src="/assets/images/paralax_image_1.png" alt="logo-dark" height="250">
        </span>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-sm" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-dark" height="90">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-sm-light" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-light" height="90">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
       <div class="d-flex justify-content-center">
        <div class="col-md-8">
            <div class="container-fluid p-0">
                <div class="card" style="padding-top: 100px;">
                    <div class="card-body">
                        <div class="content">
                            <!-- Start container-fluid -->
                            @if ($record->isNotEmpty())
                                <div class="container-fluid">
                                    <div class="col-md-12 mb-3">
                                        <h1 class="header-title mb-3">OM Customers Complaint Request</h1>
                                        <p class="header-title mb-3">Request Number : <?= $record['request_no'] ?></p>
                                    
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="header-title mb-3">Documents Attached to Request</h4>
                                            
                                            
                                            <div class="panel-body panel-form">
                                                <!-- <div class="row"> -->
                                                <!-- <div class="col-lg-12 col-md-12 "> -->
                                                <div class="form-group">
                                                    <input type="text" id="customer_name" class="inputfocus" name="customer_name" value="<?= $record['name'] ?>" readonly/>
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Name <i>(Kindly enter name and surname)</i> * :</b></label>
                                                    <!-- <i>(Kindly enter name and surname)</i> -->
                                                </div>
                                                <!-- </div> -->
                                                <!-- <div class="col-lg-12 col-md-12 "> -->
                                                <div class="form-group mb-lg">
                                                    <!-- <div class="input-group input-group-icon"> -->
                                                    <input name="address" id="address" class="inputfocus" type="text" value="<?= $record['address'] ?>" readonly/>
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Address * :</b></label>
                                                    
                                                    
                                                </div>
                                                <!-- </div> -->
                                                <!-- <div class="col-lg-12 col-md-12 "> -->
                                                <div class="form-group mb-lg">
                                                    <input name="phone_number" type="text"  class="inputfocus" value="<?= $record['phone_number'] ?>" readonly />
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Phone Number :</b></label>
                                                </div>
                                                <!-- </div> -->
                                                <!-- <div class="col-lg-12 col-md-12 "> -->
                                                <div class="form-group mb-lg">
                                                    <input name="email_address" type="email"  class="inputfocus"  value="<?= $record['email'] ?>" readonly  />
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Email Address * :</b></label>
                                                </div>
                                                <!-- </div> -->
                                                <!-- <div class="col-lg-12 col-md-12 "> -->
                                                <div class="form-group mb-lg">
                                                    <input name="policy_number" type="text"  class="inputfocus"  value="<?= $record['policy_number'] ?>" readonly />
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Policy Number * :</b></label>
                                                </div>
        
                                                <div class="form-group mb-lg">
                                                    <input name="catgeory" type="text"  class="inputfocus"  value="<?= $record->tbl_complaints_categories['name'] ?>" readonly />
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Category* :</b></label>
                                                </div>
                                            
                                            
                                                <div class="form-group mb-lg">
                                                    <textarea name="description" rows="3" type="text"  class="inputfocus" readonly><?= $record['description'] ?></textarea>
                                                    <label class="control-label col-md-12 col-sm-12" for="customer_name"><b> Details of Complaints :</b></label>
                                                </div>
                                            
                                            
                                            </div>
                                        
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            @else
                                <strong>No content found</strong>
                            @endif
                            <!-- end container-fluid -->
                        </div>
                        
                    </div>
                </div>
               
            </div>
            <!-- end container -->
        </div>
       </div>

        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('assets/js/app.js')}}"></script>


    </body>
</html>

