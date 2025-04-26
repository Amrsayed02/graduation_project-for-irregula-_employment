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
        input[type="color"] {
            height: 40px;
            padding: 2px;
        }

        #current_image {
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 4px;
        }

        .color-box {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
    <style>
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
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العماليات /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    المهن</span>
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
                {{-- <div class="col-sm-6 col-md-4 col-xl-3">
                    <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal"
                        href="#modaldemo8">اضافة فئة</a>
                </div> --}}
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0">إدارة المهن</h4>
                                    <div class="d-flex gap-2">
                                        <!-- Add Category Button -->
                                        <a class="btn btn-primary" data-effect="effect-scale" data-toggle="modal"
                                            href="#modaldemo8">
                                            <i class="fas fa-plus-circle ml-1"></i>
                                            إضافة فئة جديدة
                                        </a>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الصورة</th>
                                    <th>اللون</th>
                                    <th>الترتيب</th>
                                    <th>المشاهدات</th>
                                    <th>الظهور</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->title }}</td>
                                        <td>
                                            @if ($category->image)
                                                <img src="{{ asset($category->image) }}" width="50" height="50"
                                                    class="img-thumbnail">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->color)
                                                <span class="color-box"
                                                    style="background-color: {{ $category->color }}"></span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $category->arange }}</td>
                                        <td>{{ $category->views }}</td>
                                        <td>
                                            <span class="badge badge-{{ $category->is_home ? 'success' : 'warning' }}">
                                                {{ $category->is_home ? 'ظاهر' : 'مخفي' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }}">
                                                {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="modal-effect btn btn-sm btn-info ml-2" data-effect="effect-scale"
                                                    data-id="{{ $category->id }}" data-title="{{ $category->title }}"
                                                    data-color="{{ $category->color }}"
                                                    data-arange="{{ $category->arange }}"
                                                    data-is_home="{{ $category->is_home }}"
                                                    data-is_active="{{ $category->is_active }}" data-toggle="modal"
                                                    href="#exampleModal2" title="تعديل">
                                                    <i class="las la-pen"></i>
                                                </a>

                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $category->id }}" data-title="{{ $category->title }}"
                                                    data-toggle="modal" href="#modaldemo9" title="حذف">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modaldemo8">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">اضافة فئة جديدة</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('professions.store') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>العنوان</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label>الصورة</label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="form-group">
                                    <label>اللون</label>
                                    <input type="color" name="color" id="color" class="form-control"
                                        value="{{ convertRGBToHex($category->color) }}">
                                </div>

                                <div class="form-group">
                                    <label>الترتيب</label>
                                    <input type="number" class="form-control" name="arange" min="1">
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="is_home" value="1" checked>
                                        ظاهر في الرئيسية
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" checked>
                                        نشط
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">تأكيد</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تعديل الفئة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('professions.update', 0) }}" method="post"
                            enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="">

                            <div class="form-group">
                                <label for="title">العنوان</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="form-group">
                                <label for="image">الصورة</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <div id="current_image" class="mt-2"></div>
                            </div>

                            <div class="form-group">
                                <label for="color">اللون</label>
                                <input type="color" name="color" id="color" class="form-control"
                                    value="{{ convertRGBToHex($category->color) }}">
                            </div>

                            <div class="form-group">
                                <label for="arange">الترتيب</label>
                                <input type="number" class="form-control" id="arange" name="arange"
                                    min="1">
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="is_home" name="is_home" value="1">
                                    ظاهر في الرئيسية
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="is_active" name="is_active" value="1">
                                    نشط
                                </label>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">تأكيد</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal" id="modaldemo9">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف الفئة</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('professions.destroy', 0) }}" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>هل أنت متأكد من عملية الحذف؟</p><br>
                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="title" id="title" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تأكيد</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>




    <script>
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                // Show file preview
                document.getElementById('filePreview').classList.remove('d-none');
                document.getElementById('fileName').textContent = file.name;
            }
        }

        function submitForm() {
            const form = document.getElementById('importForm');
            const progressBar = document.getElementById('uploadProgress');
            const progressBarInner = progressBar.querySelector('.progress-bar');

            // Show progress bar
            progressBar.classList.remove('d-none');

            // Create FormData object
            const formData = new FormData(form);

            // Send AJAX request
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
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الرفع بنجاح',
                        text: 'تم استيراد البيانات بنجاح',
                        confirmButtonText: 'حسناً'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء رفع الملف',
                        confirmButtonText: 'حسناً'
                    });
                    cancelUpload();
                }
            });
        }

        function cancelUpload() {
            // Reset form and hide preview
            document.getElementById('importForm').reset();
            document.getElementById('filePreview').classList.add('d-none');
            document.getElementById('uploadProgress').classList.add('d-none');
        }
    </script>





    <script>
        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var title = button.data('title')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #title').val(title);
        });

        // Edit Modal
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Set values to form fields
            modal.find('#id').val(button.data('id'));
            modal.find('#title').val(button.data('title'));
            var color = button.data('color');
            if (color.indexOf('rgb') !== -1) {
                color = rgbToHex(color);
            }
            modal.find('#color').val(color);
            modal.find('#arange').val(button.data('arange'));
            modal.find('#is_home').prop('checked', button.data('is_home') == 1);
            modal.find('#is_active').prop('checked', button.data('is_active') == 1);

            // Show current image if exists
            if (button.data('image')) {
                var imageHtml = '<img src="' + button.data('image') + '" class="img-thumbnail" width="100">';
                modal.find('#current_image').html('الصورة الحالية: ' + imageHtml);
            } else {
                modal.find('#current_image').html('لا توجد صورة');
            }
        }); // Helper function to convert RGB to Hex in JavaScript
        function rgbToHex(rgb) {
            // Remove 'rgb()' and split the values
            var values = rgb.match(/\d+/g);
            if (values.length === 3) {
                var r = parseInt(values[0]);
                var g = parseInt(values[1]);
                var b = parseInt(values[2]);

                return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
            }
            return rgb;
        }
    </script>

    <style>
        input[type="color"] {
            height: 40px;
            padding: 2px;
        }

        #current_image {
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 4px;
        }
    </style>
@endsection
