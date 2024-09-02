<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update News</title>
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
                            <li class="breadcrumb-item"><a>Edit News</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Row end -->

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Edit News</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{route('news-update', $editnewsdata->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="newscategory" class="col-xs-2 col-form-label form-control-label">News Category *
                                    @error('newscategory')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <select name="newscategory" id="newscategory" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach (App\Models\News_category::where('is_active',1)->get() as $newscategory)
                                        <option value="{{$newscategory->id}}" {{ $editnewsdata->newscategory == $newscategory->id ? 'selected' : '' }}>
                                            {{$newscategory->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-xs-2 col-form-label form-control-label">Title *
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $editnewsdata->title) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-xs-2 col-form-label form-control-label">Description
                                    @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" required>{{ old('description', $editnewsdata->description) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label form-control-label">News Image
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="image" id="imageInput" accept="image/*">
                                    <img id="imagePreview" class="img-fluid mt-2" style="height: 120px;width: 266px;" src="{{ $editnewsdata->image ? url('images/news/' . $editnewsdata->image) : url('blank.png') }}" alt="Preview">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video_url" class="col-xs-2 col-form-label form-control-label">Video URL *
                                    @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="video_url" id="video_url" value="{{ old('video_url', $editnewsdata->video_url) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-xs-2 col-form-label form-control-label">Status
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="status" id="status">
                                        <option value="1" {{ $editnewsdata->status == 1 ? 'selected' : '' }}>Publish</option>
                                        <option value="0" {{ $editnewsdata->status == 0 ? 'selected' : '' }}>UnPublish</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Update</button>
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

</body>
</html>
@endsection
