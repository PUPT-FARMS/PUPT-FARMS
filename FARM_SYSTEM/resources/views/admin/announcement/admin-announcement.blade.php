<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.admin-header')
</head>

<body>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')


    <title>Announcement</title>
    <style>
        .toggle-dropdown {
            position: absolute;
            margin-left: 20px;
            top: 100%;
            transform: translateX(-100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            border-radius: 4px;
            z-index: 1000;
        }
    </style>
</head>

<body>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')


    <title>Announcement</title>
    <style>
        .toggle-dropdown {
            position: absolute;
            margin-left: 20px;
            top: 100%;
            transform: translateX(-100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            border-radius: 4px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    @include('partials.admin-sidebar')
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')


    <title>Announcement</title>
    <style>
        .toggle-dropdown {
            position: absolute;
            margin-left: 20px;
            top: 100%;
            transform: translateX(-100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            border-radius: 4px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    @include('partials.admin-sidebar')
    <div id="loading-spinner" class="loading-spinner">
        <div class="spinner"></div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('partials.admin-header')


    <title>Announcement</title>
    <style>
        .toggle-dropdown {
            position: absolute;
            margin-left: 20px;
            top: 100%;
            transform: translateX(-100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            border-radius: 4px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    @include('partials.admin-sidebar')
    <div id="loading-spinner" class="loading-spinner">
        <div class="spinner"></div>
    </div>
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Announcement</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="#!" class="breadcrumb-link text-secondary">Maintenance</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('admin.announcement.admin-announcement') }}"
                                                class="breadcrumb-link" style="color: #3d405c;">Announcement</a>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <!-- Create Announcement Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.announcement.add-announcement') }}" class="btn btn-primary mb-3">
                                <i class="fas fa-plus"></i> Create Announcement
                            </a>
                            <div class="d-none d-md-block col-md-4 col-sm-6">
                                <input type="text" id="search" class="form-control ml-3 mb-3"
                                    placeholder="Search announcements..." />
                            </div>
                        </div>
                        <!-- Mobile search input -->
                        <div class="d-md-none mt-3">
                            <input type="text" id="search-mobile" class="form-control mb-3"
                                placeholder="Search announcements..." />
                        </div>


                        <!-- Success Message -->
                        @if (session('success'))
                            <div id="success-message"
                                class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Announcements List -->
                        <div id="announcements-list">
                            @if ($announcements->isEmpty())
                                <div class="alert alert-info text-center" role="alert">
                                    No announcements found.
                                </div>
                            @else
                                @foreach ($announcements as $announcement)
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $announcement->subject }}</h5>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($announcement->created_at)->setTimezone('Asia/Manila')->format('F j, Y, g:i a') }}
                                                </small>
                                                <div class="mt-2">
                                                    <span class="text-muted">To:</span>
                                                    @foreach ($announcement->displayEmails as $email)
                                                        <span
                                                            class="badge bg-light text-dark">{{ $email }}</span>
                                                        @if (!$loop->last)
                                                            <span class="mx-1">,</span>
                                                        @endif
                                                    @endforeach
                                                    @if ($announcement->moreEmailsCount > 0)
                                                        <span class="text-muted">and
                                                            {{ $announcement->moreEmailsCount }} more</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                @if ($announcement->published)
                                                    <span class="badge badge-success mr-3">Published</span>
                                                @else
                                                    <span class="badge badge-warning mr-3">Unpublished</span>
                                                @endif
                                                <div class="dropdown">
                                                    <i class="fas fa-ellipsis-h" role="button"
                                                        id="dropdownMenuButton{{ $announcement->id_announcement }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="cursor: pointer;"></i>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton{{ $announcement->id_announcement }}">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.announcement.edit-announcement', $announcement->id_announcement) }}">
                                                                <i class="fas fa-edit me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item delete-btn"
                                                                data-id="{{ $announcement->id_announcement }}">
                                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                                            </button>
                                                        </li>
                                                        @if ($announcement->published)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.announcement.unpublish-announcement', $announcement->id_announcement) }}">
                                                                    <i class="fas fa-times-circle me-2"></i> Unpublish
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.announcement.publish-announcement', $announcement->id_announcement) }}">
                                                                    <i class="fas fa-check-circle me-2"></i> Publish
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-text">{!! $announcement->message !!}</div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pagination -->
                                <div class="d-flex justify-content-between align-items-center my-4">
                                    <div class="pagination-info">
                                        Showing {{ $announcements->firstItem() ?? 0 }} to
                                        {{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} results
                                    </div>

                                    @if ($announcements->hasPages())
                                        <nav aria-label="Announcements pagination">
                                            <ul class="pagination mb-0">
                                                {{-- Previous Page Link --}}
                                                @if ($announcements->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">
                                                            <i class="fas fa-chevron-left"></i>
                                                        </span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $announcements->previousPageUrl() }}"
                                                            rel="prev">
                                                            <i class="fas fa-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($announcements->getUrlRange(1, $announcements->lastPage()) as $page => $url)
                                                    @if ($page == $announcements->currentPage())
                                                        <li class="page-item active">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link"
                                                                href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($announcements->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $announcements->nextPageUrl() }}"
                                                            rel="next">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end wrapper  -->
                    <!-- ============================================================== -->
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
</body>
</html>