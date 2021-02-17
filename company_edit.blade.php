<?php
    #var_dump($company_sectors_norms);
    #exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Company Edition | Audit Suit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <!-- Datatables css -->
    <!-- third party css -->
    <link href="{{ URL::asset('assets/css/vendor/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/vendor/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/vendor/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ URL::asset('assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <!-- General CSS -->
    <link href="{{ URL::asset('/css/app.css') }}" rel="stylesheet">
</head>

<body class="loading" data-layout="detached" data-layout-config='{"leftSidebarCondensed":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="cargandoPagina"></div>

    <!-- Topbar Start -->
    @include('general/menu_topbar')
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            @include('general/menu_sidebar_left')
            <!-- Left Sidebar End -->

            <div class="content-page">
                <div class="content">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> --}}
                                        <li class="breadcrumb-item"><a href="{{ URL::to('companies') }}">Companies</a></li>
                                        <li class="breadcrumb-item active">Administration</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Company management</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->





                    <!-- star content SCONSA -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">{{ $basics->business_name }}</h4>
                                    <p class="text-muted font-14">
                                        Here you can manage the companies.
                                    </p>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Just use data attribute data-simplebar and add max-height: **px oh fix height -->
                                            {{-- <div data-simplebar data-simplebar-lg style="min-height: 500px; max-height: 550px;"> --}}



                                            <!-- Tabs Pane Area -->
                                            <ul class="nav nav-tabs mb-3">
                                                <li class="nav-item">
                                                    <a href="#basics" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                                        <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Basics</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#phone" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Phones</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sitios" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Sitios</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#sectors" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Sectors</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#socialreason" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Social Reason</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#scope" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                        <span class="d-none d-md-block">Scopes</span>
                                                    </a>
                                                </li>

                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane show active" id="basics">
                                                    @include('companies/tap_pane_company_basics')
                                                </div>
                                                <div class="tab-pane" id="phone">
                                                    @include('companies/tap_pane_company_phone')
                                                </div>
                                                <div class="tab-pane" id="sitios">
                                                    @include('companies/tap_pane_company_address')
                                                </div>
                                                <div class="tab-pane" id="sectors">
                                                    @include('companies/tap_pane_company_sectors')
                                                </div>
                                                <div class="tab-pane" id="socialreason">
                                                    @include('companies/tap_pane_company_socialreason')
                                                </div>
                                                <div class="tab-pane" id="scope">
                                                    @include('companies/tap_pane_company_scopes')
                                                </div>
                                            </div>
                                            <!-- ./Tabs Pane Area -->



























































                                            {{-- </div> --}} <!-- ./scroll -->
                                        </div>
                                    </div>

                                    {{-- <hr> --}}






                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div><!-- end col -->
                    </div>
                    <!-- end content SCONSA -->





                </div> <!-- End Content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                2018 - 2020 © Hyper - Coderthemes.com
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-right footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->













    <!-- Modal detail -->
    <div class="modal fade" id="modal-see-detail" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">Company detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">

                            <!-- Invoice Logo-->
                            <div class="clearfix">
                                <div class="float-left mb-3">
                                    <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="18">
                                </div>
                                <div class="float-right">
                                    <h4 class="m-0 d-print-none">Invoice</h4>
                                </div>
                            </div>

                            <!-- Invoice Detail-->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="float-left mt-3">
                                        <p><b>Hello, Cooper Hobson</b></p>
                                        <p class="text-muted font-13">Please find below a cost-breakdown for the recent work completed. Please make payment at your earliest convenience, and do not hesitate to contact me with any questions.</p>
                                    </div>

                                </div><!-- end col -->
                                <div class="col-sm-4 offset-sm-2">
                                    <div class="mt-3 float-sm-right">
                                        <p class="font-13"><strong>Order Date: </strong> &nbsp;&nbsp;&nbsp; Jan 17, 2018</p>
                                        <p class="font-13"><strong>Order Status: </strong> <span class="badge badge-success float-right">Paid</span></p>
                                        <p class="font-13"><strong>Order ID: </strong> <span class="float-right">#123456</span></p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->

                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <h6>Billing Address</h6>
                                    <address>
                                        Lynne K. Higby<br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        <abbr title="Phone">P:</abbr> (123) 456-7890
                                    </address>
                                </div> <!-- end col-->

                                <div class="col-sm-4">
                                    <h6>Shipping Address</h6>
                                    <address>
                                        Cooper Hobson<br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        <abbr title="Phone">P:</abbr> (123) 456-7890
                                    </address>
                                </div> <!-- end col-->

                                <div class="col-sm-4">
                                    <div class="text-sm-right">
                                        <img src="{{ URL::asset('assets/images/barcode.png') }}" alt="barcode-image" class="img-fluid mr-2">
                                    </div>
                                </div> <!-- end col-->
                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table mt-4">
                                            <thead>
                                                <tr><th>#</th>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Cost</th>
                                                    <th class="text-right">Total</th>
                                                </tr></thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <b>Laptop</b> <br>
                                                            Brand Model VGN-TXN27N/B
                                                            11.1" Notebook PC
                                                        </td>
                                                        <td>1</td>
                                                        <td>$1799.00</td>
                                                        <td class="text-right">$1799.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>
                                                            <b>Warranty</b> <br>
                                                            Two Year Extended Warranty -
                                                            Parts and Labor
                                                        </td>
                                                        <td>3</td>
                                                        <td>$499.00</td>
                                                        <td class="text-right">$1497.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>
                                                            <b>LED</b> <br>
                                                            80cm (32) HD Ready LED TV
                                                        </td>
                                                        <td>2</td>
                                                        <td>$412.00</td>
                                                        <td class="text-right">$824.00</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div> <!-- end table-responsive-->
                                    </div> <!-- end col -->
                                </div>
                                <!-- end row -->

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="clearfix pt-3">
                                            <h6 class="text-muted">Notes:</h6>
                                            <small>
                                                All accounts are to be paid within 7 days from receipt of
                                                invoice. To be paid by cheque or credit card or direct payment
                                                online. If account is not paid within 7 days the credits details
                                                supplied as confirmation of work undertaken will be charged the
                                                agreed quoted fee noted above.
                                            </small>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="float-right mt-3 mt-sm-0">
                                            <p><b>Sub-total:</b> <span class="float-right">$4120.00</span></p>
                                            <p><b>VAT (12.5):</b> <span class="float-right">$515.00</span></p>
                                            <h3>$4635.00 USD</h3>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div> <!-- end col -->
                                </div>
                                <!-- end row-->

                                <!-- <div class="d-print-none mt-4">
                                    <div class="text-right">
                                        <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a>
                                        <a href="javascript: void(0);" class="btn btn-info">Submit</a>
                                    </div>
                                </div> -->
                                <!-- end buttons -->

                            </div> <!-- end card-body-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- end modal-->













        <!-- Right Sidebar -->
        @include('general/menu_sidebar_right')
        <!-- /Right-bar -->

        <div class="rightbar-overlay"></div>


        <!-- bundle -->
        <script src="{{ URL::asset('assets/js/vendor.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/app.min.js') }}"></script>

        <!-- third party js -->
        <script src="{{ URL::asset('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/dataTables.responsive.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/dataTables.buttons.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/buttons.html5.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/buttons.flash.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/buttons.print.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/dataTables.select.min.js') }}"></script>
        <!-- third party js ends -->

        <!-- demo app -->
            <script src="{{ URL::asset('assets/js/pages/demo.datatable-init.js') }}"></script>
        <!-- end demo js-->

        <script src="{{ URL::asset('js/globals.js') }}"></script>
        <script src="{{ URL::asset('js/functions.js') }}"></script>
        <script src="{{ URL::asset('js/companies.js') }}"></script>
    </body>
</html>