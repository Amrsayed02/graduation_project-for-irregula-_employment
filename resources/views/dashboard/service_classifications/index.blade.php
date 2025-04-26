@extends('layouts.master')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 5px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #28a745;
            border-radius: 10px;
        }

        #filePreview {
            transition: all 0.3s ease;
        }

        #filePreview .card {
            border: 1px dashed #28a745;
        }

        .alert {
            border-radius: 8px;
        }

        .fa-file-excel {
            margin-right: 10px;
        }

        /* CSS for image container */
        .image-container {
            width: 50px;
            height: 50px;
            position: relative;
            overflow: hidden;
            border-radius: 50%;
        }

        /* CSS for the circular image */
        .image-container img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العماليات /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    الاسئله</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>التاريخ</th>
                                    <th>الوقت</th>
                                    <th>المستخدم</th>
                                    <th>مقدم الخدمة</th>
                                    <th>السعر</th>
                                    <th>الحالة</th>
                                    <th>توقيع المستخدم</th>
                                    <th>توقيع مقدم الخدمة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $reservation->date }}</td>
                                        <td>{{ $reservation->time }}</td>
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>{{ $reservation->vendor->name }}</td>
                                        <td>{{ $reservation->price }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $reservation->status == 'confirmed'
                                                    ? 'success'
                                                    : ($reservation->status == 'pending'
                                                        ? 'warning'
                                                        : ($reservation->status == 'completed'
                                                            ? 'info'
                                                            : 'danger')) }}">
                                                {{ $reservation->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $reservation->user_signup ? 'success' : 'warning' }}">
                                                {{ $reservation->user_signup ? 'موقع' : 'غير موقع' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $reservation->vendor_signup ? 'success' : 'warning' }}">
                                                {{ $reservation->vendor_signup ? 'موقع' : 'غير موقع' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal"
                                                data-id="{{ $reservation->id }}" data-date="{{ $reservation->date }}"
                                                data-time="{{ $reservation->time }}"
                                                data-user_id="{{ $reservation->user_id }}"
                                                data-vendor_id="{{ $reservation->vendor_id }}"
                                                data-price="{{ $reservation->price }}"
                                                data-description="{{ $reservation->description }}"
                                                data-status="{{ $reservation->status }}"
                                                data-user_signup="{{ $reservation->user_signup }}"
                                                data-vendor_signup="{{ $reservation->vendor_signup }}">
                                                <i class="las la-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $reservation->id }}"
                                                data-date="{{ $reservation->date }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="modaldemo8">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة حجز جديد</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('service_classifications.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>التاريخ</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>الوقت</label>
                                <input type="time" name="time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>المستخدم</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">اختر المستخدم</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>مقدم الخدمة</label>
                                <select name="vendor_id" class="form-control" required>
                                    <option value="">اختر مقدم الخدمة</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>السعر</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>الوصف</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending">قيد الانتظار</option>
                                    <option value="confirmed">مؤكد</option>
                                    <option value="completed">مكتمل</option>
                                    <option value="cancelled">ملغي</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="user_signup" value="1">
                                    توقيع المستخدم
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="vendor_signup" value="1">
                                    توقيع مقدم الخدمة
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تعديل الحجز</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('service_classifications.update', 0) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="reservation_id">

                        <div class="modal-body">
                            <div class="form-group">
                                <label>التاريخ</label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>الوقت</label>
                                <input type="time" name="time" id="time" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>المستخدم</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">اختر المستخدم</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>مقدم الخدمة</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value="">اختر مقدم الخدمة</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>السعر</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>الوصف</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="pending">قيد الانتظار</option>
                                    <option value="confirmed">مؤكد</option>
                                    <option value="completed">مكتمل</option>
                                    <option value="cancelled">ملغي</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="user_signup"
                                        name="user_signup" value="1">
                                    <label class="custom-control-label" for="user_signup">توقيع المستخدم</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="vendor_signup"
                                        name="vendor_signup" value="1">
                                    <label class="custom-control-label" for="vendor_signup">توقيع مقدم الخدمة</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تحديث</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">حذف الحجز</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('service_classifications.destroy', 0) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete_id">
                        <div class="modal-body">
                            <p>هل أنت متأكد من حذف هذا الحجز؟</p>
                            <p id="delete_date"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">حذف</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                document.getElementById('filePreview').classList.remove('d-none');
                document.getElementById('fileName').textContent = file.name;
            }
        }

        function submitForm() {
            const form = document.getElementById('importForm');
            const progressBar = document.getElementById('uploadProgress');
            const progressBarInner = progressBar.querySelector('.progress-bar');

            progressBar.classList.remove('d-none');

            const formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            progressBarInner.style.width = percent + '%';
                            progressBarInner.textContent = percent + '%';
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'حدث خطأ أثناء رفع الملف';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: errorMessage,
                        confirmButtonText: 'حسناً'
                    });
                    cancelUpload();
                }
            });
        }

        function cancelUpload() {
            document.getElementById('importForm').reset();
            document.getElementById('filePreview').classList.add('d-none');
            document.getElementById('uploadProgress').classList.add('d-none');
        }

        // Edit Modal
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Set values to form fields
            modal.find('#reservation_id').val(button.data('id'));
            modal.find('#date').val(button.data('date'));
            modal.find('#time').val(button.data('time'));
            modal.find('#user_id').val(button.data('user_id'));
            modal.find('#vendor_id').val(button.data('vendor_id'));
            modal.find('#price').val(button.data('price'));
            modal.find('#description').val(button.data('description'));
            modal.find('#status').val(button.data('status'));
            modal.find('#user_signup').prop('checked', button.data('user_signup') == 1);
            modal.find('#vendor_signup').prop('checked', button.data('vendor_signup') == 1);
        });

        // Delete Modal
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#delete_id').val(button.data('id'));
            modal.find('#delete_date').text('تاريخ الحجز: ' + button.data('date'));
        });
    </script>
    <style>
        .custom-control-input:checked~.custom-control-label::before {
            border-color: #28a745;
            background-color: #28a745;
        }

        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .custom-checkbox {
            padding-right: 1.5rem;
        }

        .custom-control-label {
            position: relative;
            margin-bottom: 0;
            vertical-align: top;
        }

        select.form-control {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input[type="date"].form-control,
        input[type="time"].form-control {
            padding: 0.375rem 0.75rem;
        }

        textarea.form-control {
            resize: vertical;
        }
    </style>
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
@endsection
