<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
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
                        <h4> News</h4>
                        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('news-list')}}">Manage News</a>
                            </li>
                            <li class="breadcrumb-item"><a>Add News</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Row end -->


            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Add News</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{route('news-store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">News Category *
                                    @error('newscategory')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror</label>
                                <div class="col-sm-10">
                                    <select name="newscategory" id="newscategory" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach (App\Models\News_category ::where('is_active',1)->get() as $newscategory)
                                        <option value="{{$newscategory->id}}">{{$newscategory->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title *
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="title" id="title" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-url-input" class="col-xs-2 col-form-label form-control-label">
                                    Description
                                    @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label form-control-label">
                                    News Image
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" required>
                                    <img id="imagePreview" class="img-fluid mt-2" style="height: 120px;width: 266px;" src="{{url('blank.png')}}" alt="Preview">
                                </div>

                                <div class="form-group row">
                                    <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Video URL *
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="video_url" id="video_url" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status" id="status">
                                            <option value="1">Publish</option>
                                            <option value="0" selected>UnPublish</option>
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

        <!-- Main content ends -->
    </div>

    <!-- Include CKEditor script -->
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
    <script>
        // Replace the <textarea> with CKEditor
        CKEDITOR.replace('description');
        CKEDITOR.replace('bottom_desc');
    </script>

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