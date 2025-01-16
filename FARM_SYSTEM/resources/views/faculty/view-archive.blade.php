<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Archive</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../../../asset/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('assets/images/pup-logo.png') }}" type="image/x-icon">
    <link href="../../../../asset/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../asset/libs/css/style.css">
    <link rel="stylesheet" href="../../../../asset/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/buttons.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/select.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/fixedHeader.bootstrap4.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .body-modal {
            max-height: 480px;
            overflow-y: auto;
        }

        .view-modal {
            max-height: 400px;
            overflow-y: auto;
        }

        .bordered-file-input {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            background-color: #fff;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        }

        .modal-dialog {
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        p {
            color: #3d405c;
        }

        strong {
            color: rgb(27, 27, 27);
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
                            <h2 class="pageheader-title">Archive Files</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#!" class="breadcrumb-link"
                                                >Menu</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('faculty.view-archive') }}"
                                                class="breadcrumb-link" style=" color: #3d405c;">Archive</a></li>
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
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header fw-bold">
                                All Archive Files
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success text-center">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form id="bulk-restore-form" action="{{ route('files.bulkUnarchive') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm mb-3"
                                        id="restore-selected">Restore</button>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <th>No.</th>
                                                    <th>Date & Time</th>
                                                    <th>Semester</th>
                                                    <th>Program</th>
                                                    <th>Course & Course Code</th>
                                                    <th>Year & Section</th>
                                                    <th>File Name</th>
                                                    <th>Status</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($uploadedFiles as $file)
                                                    <tr>
                                                        <td><input type="checkbox" name="file_ids[]"
                                                                value="{{ $file->courses_files_id }}"
                                                                class="file-checkbox"></td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($file->created_at)->locale('en_PH')->format('F j, Y, g:i A') }}
                                                        </td>
                                                        <td>{{ $file->courseSchedule->sem_academic_year }}</td>
                                                        <td>{{ $file->courseSchedule->program }}</td>
                                                        <td>{{ $file->subject }} -
                                                            {{ $file->courseSchedule->course_code }}</td>
                                                        <td>{{ $file->courseSchedule->year_section }}</td>
                                                        <td>
                                                            <a href="{{ Storage::url('/' . $file->files) }}"
                                                                target="_blank"
                                                                style="color: rgb(65, 65, 231); text-decoration: underline;">
                                                                {{ $file->original_file_name }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if ($file->status === 'To Review')
                                                                <span
                                                                    class="badge badge-primary">{{ $file->status }}</span>
                                                            @elseif($file->status === 'Approved')
                                                                <span
                                                                    class="badge badge-success">{{ $file->status }}</span>
                                                            @elseif($file->status === 'Declined')
                                                                <span
                                                                    class="badge badge-danger">{{ $file->status }}</span>
                                                                @if ($file->declined_reason)
                                                                    <div class="mt-2">Declined Reason:
                                                                        {{ $file->declined_reason }}</div>
                                                                @endif
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary">{{ $file->status }}</span>
                                                            @endif
                                                        </td>
                                                        {{-- <td>
                                                            <button type="button" class="btn btn-warning btn-sm mr-1 restore-single" data-file-id="{{ $file->courses_files_id }}">Restore</button>
                                                        </td> --}}
                                                    </tr>
                                                @empty

                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
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

       <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const fileCheckboxes = document.querySelectorAll('.file-checkbox');
            const bulkRestoreForm = document.getElementById('bulk-restore-form');
            const restoreSelectedButton = document.getElementById('restore-selected');

            selectAllCheckbox.addEventListener('change', function() {
                fileCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateRestoreButtonVisibility();
            });

            fileCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateRestoreButtonVisibility);
            });

            function updateRestoreButtonVisibility() {
                const checkedBoxes = document.querySelectorAll('.file-checkbox:checked');
                restoreSelectedButton.style.display = checkedBoxes.length > 0 ? 'inline-block' : 'none';
            }

            bulkRestoreForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const checkedBoxes = document.querySelectorAll('.file-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'No files selected',
                        text: 'Please select at least one file to restore.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to restore the selected files.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkRestoreForm.submit();
                    }
                });
            });

            updateRestoreButtonVisibility();
        });
        </script>
</body>

</html>
