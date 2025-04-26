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
    <style>
        /* CSS for image container */
        .image-container {
            width: 50px;
            height: 50px;
            position: relative;
            overflow: hidden;
            border-radius: 50%;
        }

        .banner-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .preview-image {
            max-width: 200px;
            margin-top: 10px;
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
                <h4 class="content-title mb-0 my-auto">الاعلانات /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">جميع
                    الاعلانات</span>
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
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">إدارة البانرات</h4>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modaldemo8">
                        <i class="fas fa-plus"></i> إضافة بانر
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>الاسم</th>
                                    <th>النوع</th>
                                    <th>الترتيب</th>
                                    <th>الرابط</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $banner)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset($banner->image) }}" class="banner-image"
                                                alt="{{ $banner->name }}">
                                        </td>
                                        <td>{{ $banner->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $banner->type == 'banner' ? 'primary' : 'info' }}">
                                                {{ $banner->type }}
                                            </span>
                                        </td>
                                        <td>{{ $banner->arrange }}</td>
                                        <td>
                                            <a href="{{ $banner->banner_url }}" target="_blank">
                                                {{ Str::limit($banner->banner_url, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $banner->status ? 'success' : 'danger' }}">
                                                {{ $banner->status ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal"
                                                data-id="{{ $banner->id }}" data-name="{{ $banner->name }}"
                                                data-type="{{ $banner->type }}" data-arrange="{{ $banner->arrange }}"
                                                data-banner_url="{{ $banner->banner_url }}"
                                                data-status="{{ $banner->status }}"
                                                data-image="{{ asset($banner->image) }}">
                                                <i class="las la-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $banner->id }}"
                                                data-name="{{ $banner->name }}">
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
                        <h5 class="modal-title">إضافة بانر جديد</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control" required
                                    onchange="previewImage(this, 'add-preview')">
                                <div id="add-preview"></div>
                            </div>

                            <div class="form-group">
                                <label>الاسم</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>النوع</label>
                                <select name="type" class="form-control" required>
                                    <option value="banner">بانر</option>
                                    <option value="ads">إعلان</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>الترتيب</label>
                                <input type="number" name="arrange" class="form-control" min="0">
                            </div>

                            <div class="form-group">
                                <label>الرابط</label>
                                <input type="url" name="banner_url" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" value="1" checked>
                                    نشط
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
                        <h5 class="modal-title">تعديل البانر</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('banners.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="banner_id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control"
                                    onchange="previewImage(this, 'edit-preview')">
                                <div id="edit-preview"></div>
                            </div>

                            <div class="form-group">
                                <label>الاسم</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>النوع</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="banner">بانر</option>
                                    <option value="ads">إعلان</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>الترتيب</label>
                                <input type="number" name="arrange" id="arrange" class="form-control"
                                    min="0">
                            </div>

                            <div class="form-group">
                                <label>الرابط</label>
                                <input type="url" name="banner_url" id="banner_url" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" id="status" value="1">
                                    نشط
                                </label>
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
                        <h5 class="modal-title">حذف البانر</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('banners.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete_id">
                        <div class="modal-body">
                            <p>هل أنت متأكد من حذف هذا البانر؟</p>
                            <p id="delete_name"></p>
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
    <script>
        const discountField = document.getElementById('type');
        const discountTimeFields = document.getElementById('subcat');

        // دالة لإظهار حقول الوقت
        function showDiscountTimeFields() {
            discountTimeFields.style.display = 'block';
        }

        // دالة لإخفاء حقول الوقت
        function hideDiscountTimeFields() {
            discountTimeFields.style.display = 'none';
        }
        let x = false;
        // ربط حدث تغيير حقل الخصم بدالتي الإظهار والإخفاء
        discountField.addEventListener('change', function() {
            // إذا كان القيمة غير صفرية، أظهر حقول الوقت، وإلا أخفها
            if (x) {
                showDiscountTimeFields();
                x = false;
            } else {
                hideDiscountTimeFields();
                x = true;
            }
        });
    </script>
    <script>
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#banner_id').val(button.data('id'));
            modal.find('#name').val(button.data('name'));
            modal.find('#type').val(button.data('type'));
            modal.find('#arrange').val(button.data('arrange'));
            modal.find('#banner_url').val(button.data('banner_url'));
            modal.find('#status').prop('checked', button.data('status') == 1);

            // Show current image
            var currentImage = button.data('image');
            if (currentImage) {
                modal.find('#edit-preview').html('<img src="' + currentImage + '" class="preview-image">');
            }
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#delete_id').val(button.data('id'));
            modal.find('#delete_name').text('الاسم: ' + button.data('name'));
        });
    </script>




    <script>
        $(document).ready(function() {
            $('.main-toggle').on('click', function() {
                $(this).toggleClass('on');
                var isToggleOn = $(this).hasClass('on');
                var url = '{{ route('banners.update-status') }}';
                var categoryId = $(this).data('banner-id');
                // Retrieve the CSRF token value from the meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        isToggleOn: isToggleOn,
                        categoryId: categoryId
                    },
                    success: function(response) {
                        console.log(response);
                        // Handle the success response
                    },
                    error: function(error) {
                        console.log(error);
                        // Handle the error response
                    }
                });
            });
        });
    </script>

    <script>
        function displaySelectedImage(event) {
            const fileInput = event.target;
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewImage = document.getElementById('preview-image');
                    previewImage.src = e.target.result;
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        document.querySelector("#image").addEventListener("change", function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector("#preview").setAttribute("src", e.target.result);
                document.querySelector("#preview").style.display = "block";
            };
            reader.readAsDataURL(this.files[0]);
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
