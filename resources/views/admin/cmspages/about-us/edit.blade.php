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
                                    <a class="nav-link" id="aboutuspage-tab" data-toggle="tab" href="#aboutuspage"
                                        role="tab" aria-controls="aboutuspage" aria-selected="false">About Us Page</a>
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
                                <div class="tab-pane" id="aboutuspage" role="tabpanel" aria-labelledby="aboutuspage-tab">
                                    <form id="contactForm" method="post" action="{{ route('update-about', $aboutus->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Banner Section</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Page Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" id="page_name"
                                                                name="page_name" placeholder="Name"
                                                                value="{{ $aboutus->page_name }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Banner
                                                            Image</label>
                                                        @if ($aboutus->banner_image != null || $aboutus->banner_image != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->banner_image) }}"
                                                                alt="banner_image" id="banner_image" style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control" name="banner_image"
                                                            id="banner_image" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Who We Are Section</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Heading</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="who_we_are_heading" name="who_we_are_heading"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->who_we_are_heading }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="who_we_are_title" name="who_we_are_title"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->who_we_are_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Description1
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="who_we_are_desc1" id="who_we_are_desc1" placeholder="Description">{{ $aboutus ? $aboutus->who_we_are_desc1 : '' }}</textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Description2
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="who_we_are_desc2" id="who_we_are_desc2" placeholder="Description">{{ $aboutus ? $aboutus->who_we_are_desc2 : '' }}</textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Who We Are Image1</label>
                                                        @if ($aboutus->who_we_are_image1 != null || $aboutus->who_we_are_image1 != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->who_we_are_image1) }}"
                                                                alt="who_we_are_image1" id="who_we_are_image1"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="who_we_are_image1" id="who_we_are_image1"
                                                            accept="image/*">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Who We Are Image2</label>
                                                        @if ($aboutus->who_we_are_image2 != null || $aboutus->who_we_are_image2 != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->who_we_are_image2) }}"
                                                                alt="who_we_are_image2" id="who_we_are_image2"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="who_we_are_image2" id="who_we_are_image2"
                                                            accept="image/*">
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Year Of Experience</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="year_of_exp" name="year_of_exp"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->year_of_exp }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Total Revenue</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="revenue_count" name="revenue_count"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->revenue_count }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Revenue
                                                            Image</label>
                                                        @if ($aboutus->revenue_image != null || $aboutus->revenue_image != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->revenue_image) }}"
                                                                alt="revenue_image" id="revenue_image"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="revenue_image" id="revenue_image"
                                                            accept="image/*">
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Increase in sales</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="sales_count" name="sales_count"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->sales_count }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Sales
                                                            Image</label>
                                                        @if ($aboutus->sales_image != null || $aboutus->sales_image != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->sales_image) }}"
                                                                alt="sales_image" id="sales_image"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="sales_image" id="sales_image"
                                                            accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="card-header px-4 py-3">
                                                <h5 class="mb-0">Edit Slogan Section</h5>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="card-body p-4">
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Heading</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="slogan_heading" name="slogan_heading"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->slogan_heading }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="input35" class=" col-form-label">Title</label>
                                                        <div class="">
                                                            <input type="text" class="form-control"
                                                                id="slogan_title" name="slogan_title"
                                                                placeholder="Title"
                                                                value="{{ $aboutus->slogan_title }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">
                                                            Video Url
                                                        </label>
                                                        <textarea class="ckeditor form-control" name="slogan_video_url" id="slogan_video_url" placeholder="Description">{{ $aboutus ? $aboutus->slogan_video_url : '' }}</textarea>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label for="input40" class="col-sm-3 col-form-label">Slogan Background Image
                                                            </label>
                                                        @if ($aboutus->slogan_bg_image != null || $aboutus->slogan_bg_image != '')
                                                            <img src="{{ asset('aboutuspage/' . $aboutus->slogan_bg_image) }}"
                                                                alt="slogan_bg_image" id="slogan_bg_image"
                                                                style="width:20%">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="slogan_bg_image" id="slogan_bg_image"
                                                            accept="image/*">
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
@endsection
