@extends('admin.layout.app', ['title' => 'Edit Product'])
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/product/form.css') }}">
    <style type="text/css">
        .product-image {
            max-width: 200px;
            margin-bottom: 10px;
        }
    </style>
@stop
@section('body')
    <h1>Edit Product</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label for="image">Current Image</label>
            @if($product->image)
                <img src="{{ env('APP_URL') }}/{{ $product->image }}" class="product-image" alt="{{ $product->name }}">
            @endif
            <input type="file" id="image" name="image" class="form-control">
            <small>Leave empty to keep current image</small>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.product.list') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@stop
