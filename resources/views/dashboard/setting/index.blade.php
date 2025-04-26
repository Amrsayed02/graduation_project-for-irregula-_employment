@extends('layouts.master')
@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')

        <br>
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
    <form action="{{ route('setting.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('POST') <!-- Use PUT method for updates -->

        <div class="row row-sm">
            <!-- Col -->
            <div class="col-lg-4">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="pl-0">
                            <div class="main-profile-overview">
                                <input type="text" value="{{ Auth::user()->id }}" name="user_id" hidden>
                                <div class="main-img-user profile-user">
                                    <img id="profile-image" alt="" src="{{ URL::asset($setting->logo ?? '') }}">
                                    <label for="image-upload" class="fas fa-camera profile-edit"></label>
                                    <input id="image-upload" type="file" style="display: none;" name="logo">
                                </div>

                                <div class="d-flex justify-content-between mg-b-20">
                                    <div>
                                        <h5 class="main-profile-name">
                                            {{ old('company_name', $setting->company_name ?? '') }}</h5>
                                        <p class="main-profile-name-text">{{ old('email', $setting->email ?? '') }}</p>
                                    </div>
                                </div>
                                <h6>السيره الذاتية</h6>
                                <div class="main-profile-bio">
                                    {{ old('biographical_information', $setting->biographical_information ?? '') }}
                                </div><!-- main-profile-bio -->

                                <hr class="mg-y-30">
                                <label class="main-content-label tx-13 mg-b-20">وسائل التواصل</label>
                                <div class="main-profile-social-list">
                                    <div class="media">
                                        <div class="media-icon bg-primary-transparent text-primary">
                                            <i class="icon ion-logo-github"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>جيتهاب</span> <a href="">{{ old('github', $setting->github ?? '') }}</a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-success-transparent text-success">
                                            <i class="icon ion-logo-twitter"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>تويتر</span> <a href="">{{ old('twitter', $setting->twitter ?? '') }}</a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-info-transparent text-info">
                                            <i class="icon ion-logo-linkedin"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>لينكدن</span> <a
                                                href="">{{ old('linkedin', $setting->linkedin ?? '') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mg-y-30">
                            </div><!-- main-profile-overview -->
                        </div>
                    </div>
                </div>
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label tx-13 mg-b-25">
                            التواصل
                        </div>
                        <div class="main-profile-contact-list">
                            <div class="media">
                                <div class="media-icon bg-primary-transparent text-primary">
                                    <i class="icon ion-md-phone-portrait"></i>
                                </div>
                                <div class="media-body">
                                    <span>الهاتف</span>
                                    <div>
                                        {{ old('company_phone', $setting->company_phone ?? '') }}
                                    </div>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-icon bg-success-transparent text-success">
                                    <i class="icon ion-logo-slack"></i>
                                </div>
                                <div class="media-body">
                                    <span>موقع المتجر</span>
                                    <div>
                                        {{ old('website_link', $setting->website_link ?? '') }}
                                    </div>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-icon bg-info-transparent text-info">
                                    <i class="icon ion-md-locate"></i>
                                </div>
                                <div class="media-body">
                                    <span>عنوان المتجر</span>
                                    <div>
                                        {{ old('company_address', $setting->company_address ?? '') }}
                                    </div>
                                </div>
                            </div>
                        </div><!-- main-profile-contact-list -->
                    </div>
                </div>
            </div>

            <!-- Col -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 main-content-label">الاسم</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">اسم المتجر</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="اسم المتجر"
                                        value="{{ old('company_name', $setting->company_name ?? '') }}" name="company_name">
                                    @error('company_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 main-content-label">معلومات الاتصال</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">البريد الاكتروني<i>(مطلوب)</i></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="البريد الاكتروني"
                                        value="{{ old('email', $setting->email ?? '') }}" name="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">رابط الموقع</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="website_link" placeholder="رابط الموقع"
                                        value="{{ old('website_link', $setting->website_link ?? '') }}">
                                    @error('website_link')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">هاتف المتجر</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="هاتف المتجر"
                                        value="{{ old('company_phone', $setting->company_phone ?? '') }}"
                                        name="company_phone">
                                    @error('company_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">عنوان المتجر</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="company_address" rows="2"
                                        placeholder="عنوان المتجر">{{ old('company_address', $setting->company_address ?? '') }}</textarea>
                                    @error('company_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 main-content-label">المعلومات التواصل الاجتماعي</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">تويتر</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="تويتر"
                                        value="{{ old('twitter', $setting->twitter ?? '') }}" name="twitter">
                                    @error('twitter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">فيسبوك</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="فيسبوك"
                                        value="{{ old('facebook', $setting->facebook ?? '') }}" name="facebook">
                                    @error('facebook')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">جوجل</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="جوجل"
                                        value="{{ old('google', $setting->google ?? '') }}" name="google">
                                    @error('google')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">لينكدن</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="لينكدن"
                                        value="{{ old('linkedin', $setting->linkedin ?? '') }}" name="linkedin">
                                    @error('linkedin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">جيتهاب</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="جيتهاب"
                                        value="{{ old('github', $setting->github ?? '') }}" name="github">
                                    @error('github')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 main-content-label">معلومات اضافيه عن المتجر</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">الوصف</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="biographical_information" rows="4"
                                        placeholder="الوصف">{{ old('biographical_information', $setting->biographical_information ?? '') }}</textarea>
                                    @error('biographical_information')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">تحديث البروفيل</button>
                    </div>
                </div>
            </div>
            <!-- /Col -->
        </div>
    </form>
@endsection
@section('js')
    <script>
        document.getElementById('image-upload').addEventListener('change', function(e) {
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profile-image').src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
@endsection
