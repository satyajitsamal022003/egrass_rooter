<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Banner</title>
</head>
@extends('layouts.admin.layout')
@section('section')

    <body>
        <div class="container-fluid">
            <!-- Main content starts -->
            <div>
                <!-- Row Starts -->
                <div class="row">
                    <div class="col-sm-12 p-0">
                        <div class="main-header">
                            <h4>General Elements</h4>
                            <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                            class="icofont icofont-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('cmspages.index') }}">CMS Page</a>
                                </li>
                                <li class="breadcrumb-item"><a>Edit Banner</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- Row end -->


                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text">Edit Banner</h5>
                        </div>
                        <div class="card-block">
                            <form method="post" action="{{ route('update-homebanner', $homebanner->id) }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title
                                        *</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="banner_title" id="banner_title"
                                            value="{{ $homebanner->banner_title }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-search-input"
                                        class="col-xs-2 col-form-label form-control-label">Description *</label>
                                    <div class="col-sm-10">
                                        <textarea class="ckeditor form-control" name="banner_desc" id="banner_desc" placeholder="Description" required>{{ $homebanner->banner_desc }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input"
                                        class="col-xs-2 col-form-label form-control-label">Button Name
                                        *</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="banner_button_name"
                                            id="banner_button_name" value="{{ $homebanner->banner_button_name }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input"
                                        class="col-xs-2 col-form-label form-control-label">Button Url
                                        *</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="banner_button_url"
                                            id="banner_button_url" value="{{ $homebanner->banner_button_url }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label form-control-label">Image</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" name="banner_image" id="imageInput"
                                            accept="image/*">
                                        <img id="imagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;"
                                            src="{{ asset('homebanner/' . $homebanner->banner_image) }}" alt="Preview">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-email-input"
                                        class="col-xs-2 col-form-label form-control-label">Status</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="is_active" id="is_active">
                                            <option value="1" {{ $homebanner->is_active == '1' ? 'selected' : '' }}>
                                                Publish</option>
                                            <option value="0" {{ $homebanner->is_active == '0' ? 'selected' : '' }}>
                                                UnPublish</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Include CKEditor script -->
        <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('banner_desc');
        </script>

        {{-- <script>
            function generateSlug(str) {
                return str.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            }

            document.getElementById('title').addEventListener('input', function() {
                const title = this.value;
                const slug = generateSlug(title);
                document.getElementById('slug').value = slug;
            });
        </script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const imageInput = document.getElementById('imageInput');
                const imagePreview = document.getElementById('imagePreview');
                imageInput.addEventListener('change', function() {
                    displayImagePreview(this, imagePreview);
                });

                function displayImagePreview(input, preview) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        preview.src = '#';
                    }
                }
            });
        </script>

    </html>
@endsection
