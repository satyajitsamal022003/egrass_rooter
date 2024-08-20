@extends('layouts.admin.layout')
@section('section')
    <div class="container-fluid">

        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Manage CMS Pages</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('cmspages.index') }}">CMS
                                Page</a>
                        </li>
                        <li class="breadcrumb-item"><a>List</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- Row end -->

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="padding: 20px;">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                        role="tab" aria-controls="home" aria-selected="true">CMS Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="homebanner-tab" data-toggle="tab" href="#homebanner"
                                        role="tab" aria-controls="homebanner" aria-selected="false">Home Banner
                                        Section</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="homepage-tab" data-toggle="tab" href="#homepage" role="tab"
                                        aria-controls="homepage" aria-selected="false">Home Page</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <form id="productForm" method="post" action="{{ route('cms-update', $cmsPage->id) }}">
                                        @csrf
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit CMS Page</h5>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <div class="card-body p-4">
                                                    <div class="mb-3">
                                                        <label for="input35" class="col-sm-3 col-form-label">Title</label>

                                                        <input type="text" class="form-control" id="title"
                                                            name="title" placeholder="CMS Page Title"
                                                            value="{{ $cmsPage ? $cmsPage->title : '' }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label"></label>
                                                        <div class="col-sm-8">
                                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                                <button type="submit"
                                                                    class="btn btn-primary px-4">Submit</button>
                                                                <button type="reset"
                                                                    class="btn btn-light px-4">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-12">
                                                <div class="card-body p-4">
                                                    <div class="mb-3">
                                                        <label for="input40" class="col-sm-3 col-form-label">Status
                                                        </label>

                                                        <select id="status" name="status" class="select2 form-select">

                                                            <option value="1"
                                                                {{ $cmsPage->status == 1 ? 'selected' : '' }}>Publish
                                                            </option>
                                                            <option value="0"
                                                                {{ $cmsPage->status == 0 ? 'selected' : '' }}>Draft
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="homebanner" role="tabpanel" aria-labelledby="homebanner-tab">
                                    <div class="row">
                                        <div class="ms-auto">
                                            <div class="btn-group m-3" style="float:right;">
                                                <button onclick="toggleForm()" class="btn btn-primary"
                                                    id="homebannerBtn">Create a New Banner</button>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="homebannerForm" method="post" action="{{ route('store-homebanner') }}"
                                        style="display:none;" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Add New Banner</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="mb-3">
                                                        <label for="input35"
                                                            class="col-sm-3 col-form-label">Title</label>
                                                        <input type="text" class="form-control" id="banner_title"
                                                            name="banner_title" placeholder="Banner Title" required>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="input40"
                                                            class="col-sm-3 col-form-label">Description</label>
                                                        <textarea class="ckeditor form-control" name="banner_desc" id="banner_desc" placeholder="Description" required></textarea>
                                                    </div>


                                                    <div class="mb-3">
                                                        <label for="input35" class="col-sm-3 col-form-label">Button
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="banner_button_name" name="banner_button_name"
                                                            placeholder="Banner Title" required>
                                                    </div>


                                                    <div class="mb-3">
                                                        <label for="input35" class="col-sm-3 col-form-label">Button
                                                            Url</label>
                                                        <input type="text" class="form-control" id="banner_button_url"
                                                            name="banner_button_url" placeholder="Banner Title" required>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="input40"
                                                            class="col-sm-3 col-form-label">Image</label>
                                                        <input type="file" class="form-control" name="banner_image"
                                                            id="banner_image" accept="image/*" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-8">
                                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                                        <button type="submit" id="submitBtn"
                                                            class="btn btn-primary px-4">Submit</button>
                                                        <button type="reset" class="btn btn-light px-4">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="card-header m-2"></div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="faqTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($homebanner)
                                                        @php($i = 0)
                                                        @foreach ($homebanner as $val)
                                                            <tr>
                                                                <td>{{ ++$i }}</td>
                                                                <td>{{ $val->banner_title ? $val->banner_title : 'NA' }}
                                                                </td>
                                                                <td><?php $content = $val->banner_desc;
                                                                $content1 = substr($content, 0, 70);
                                                                echo $content1 . '...'; ?></td>
                                                                <td><img src="{{ asset('homebanner/' . $val->banner_image) }}"
                                                                        width="50px" height="35px"></td>
                                                                <td class="center">
                                                                    <a class="btn btn-success"
                                                                        href="{{ route('edit-homebanner', $val->id) }}"
                                                                        title="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>

                                                                    <form
                                                                        action="{{ route('homebanner-destroy', $val->id) }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this banner?');"
                                                                            title="Delete">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </form>

                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="homepage" role="tabpanel" aria-labelledby="homepage-tab">
                                    <form id="contactForm" method="post"
                                        action="{{ route('update-home', $homePage->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Home About Section</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">About
                                                            Heading</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" id="about_heading"
                                                                name="about_heading" placeholder="Name"
                                                                value="{{ $homePage->about_heading }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">About
                                                            Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" id="about_title"
                                                                name="about_title" placeholder="Name"
                                                                value="{{ $homePage->about_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">About
                                                            Description
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="about_desc" id="about_desc" placeholder="Description">{{ $homePage ? $homePage->about_desc : '' }}</textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">About
                                                            Image</label>
                                                        @if ($homePage->about_image != null || $homePage->about_image != '')
                                                            <img src="{{ asset('homepage/' . $homePage->about_image) }}"
                                                                alt="about_image" id="about_image"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="about_image" id="about_image"
                                                            accept="image/*">
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">About Button Name</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="about_btn" name="about_btn"
                                                                placeholder="Name"
                                                                value="{{ $homePage->about_btn }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">About Button Url</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="about_btn_url" name="about_btn_url"
                                                                placeholder="Name"
                                                                value="{{ $homePage->about_btn_url }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Why Choose Us</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="why_choose_us_title" name="why_choose_us_title"
                                                                placeholder="Title"
                                                                value="{{ $homePage->why_choose_us_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Description
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="why_choose_us_desc" id="why_choose_us_desc" placeholder="Description">{{ $homePage ? $homePage->why_choose_us_desc : '' }}</textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Why Choose Us
                                                            Image</label>
                                                        @if ($homePage->why_choose_us_image != null || $homePage->why_choose_us_image != '')
                                                            <img src="{{ asset('homepage/' . $homePage->why_choose_us_image) }}"
                                                                alt="why_choose_us_image" id="why_choose_us_image"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="why_choose_us_image" id="why_choose_us_image"
                                                            accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Run For Office</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="runforoffice_title" name="runforoffice_title"
                                                                placeholder="Title"
                                                                value="{{ $homePage->runforoffice_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Description
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="runforoffice_desc" id="runforoffice_desc" placeholder="Description">{{ $homePage ? $homePage->runforoffice_desc : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Quick Software Overview</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="overview_title" name="overview_title"
                                                                placeholder="Title"
                                                                value="{{ $homePage->overview_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Description
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="overview_desc" id="overview_desc" placeholder="Description">{{ $homePage ? $homePage->overview_desc : '' }}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-3 col-form-label"></label>
                                                        <div class="col-sm-8">
                                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                                <button type="submit" id="submitBtn"
                                                                    class="btn btn-primary px-4">Submit</button>
                                                                <button type="reset"
                                                                    class="btn btn-light px-4">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script>
        CKEDITOR.replace('section1_hm_content', {
            allowedContent: true,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.replace('section2_hm_content', {
            allowedContent: true,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.replace('section1_content', {
            allowedContent: true,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.replace('section2_content', {
            allowedContent: true,
            extraPlugins: 'colorbutton'
        });
    </script> -->
    <script>
        function toggleForm() {
            const form = document.getElementById('homebannerForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
    {{-- <script type="text/javascript">

        $("#title").keyup(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#title").keydown(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $(document).ready(function() {

            //Banner Delete Alert
            $("body").on("click", ".trash1", function() {
                var current_object = $(this);
                swal({
                    title: "Are you sure?",
                    text: "It will be Deleted Permanently!",
                    type: "success",
                    showCancelButton: true,
                    dangerMode: true,
                    cancelButtonClass: '#DD6B55',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ok!',
                }, function(result) {
                    if (result) {
                        var action = current_object.attr('data-action');
                        var token = jQuery('meta[name="csrf-token"]').attr('content');
                        var id = current_object.attr('data-id');
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            url: action,
                            type: 'post',
                            data: {
                                'id': id
                            },
                            success: function(result) {
                                if (result.status) {
                                    $("#home_loan_" + id).hide();
                                    swal("Nice!", "Deleted Successfully", "success");
                                } else {
                                    swal("Error!",
                                        "Something went wrong !! Please Try again later",
                                        "error");
                                }
                            },
                            error: function(e) {
                                swal("Error!",
                                    "Something went wrong !! Please Try again later",
                                    "error");
                            }
                        });
                    }
                });
            });
        });
    </script> --}}
@endsection
