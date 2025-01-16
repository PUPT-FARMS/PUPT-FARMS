<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.faculty-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dashboard</title>
    <style>
        .icon {
            font-size: 30px;
            color: #800000;
        }
    </style>
</head>

<body>
    @include('partials.faculty-sidebar')
    <div id="loading-spinner" class="loading-spinner">
        <div class="spinner"></div>
    </div>
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title"> Dashboard</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#!" class="breadcrumb-link"
                                                >Menu</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('faculty.faculty-dashboard') }}"
                                                class="breadcrumb-link" style=" color: #3d405c;">Dashboard</a></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->
                <div class="ecommerce-widget">
                    <div class="row">
                        <!-- Total Faculty Card -->
                        <!-- Completed Reviews Card -->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <h5 class="text-muted">Total of Approved</h5>
                                        <h2 class="mb-0">{{ $approvedCount }}</h2>
                                    </div>
                                    <div class="float-right icon-circle-medium icon-box-lg bg-success-light mt-1">
                                        <i class="fa fa-check-circle fa-fw fa-sm text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total of Pending Reviews Card -->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <h5 class="text-muted">Total of Pending Review</h5>
                                        <h2 class="mb-0">{{ $toReviewCount }}</h2>
                                    </div>
                                    <div class="float-right icon-circle-medium icon-box-lg bg-primary-light mt-1">
                                        <i class="fa fa-tasks fa-fw fa-sm text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Storage Card -->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <h5 class="text-muted">Storage Used</h5>
                                        <h2 class="mb-0"> {{ $formattedTotalStorageUsed }}</h2>
                                    </div>
                                    <div class="float-right icon-circle-medium icon-box-lg bg-brand-light mt-1">
                                        <i class="fa fa-database fa-fw fa-sm text-brand"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart Section -->
                    <div class="row">
                        <div class="col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Submitted Status per Folder</h5>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <canvas id="statusBarChart" style="width: 100%; height: 100px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Storage Usage</h5>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <canvas id="storageChart" style="width: 100%;  height: 20px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Passed Files Percentage per Main Requirements</h5>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <canvas id="folderBarChart" style="width: 100%; "></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- end wrapper  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end main wrapper  -->
        <!-- ============================================================== -->
</body>

</html>
