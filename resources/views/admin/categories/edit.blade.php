@extends('admin.master')
@inject('model' , 'App\Models\Category')

@section('title')
    Edit Category 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('category.index'))}}">categories</a></li>
            <li class="breadcrumb-item active">edit category</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
    @include('admin.includes.validation_errors')

    {!!Form::model($model , [
        'route'  => ['category.update' , $category->id] ,
        'class'  => 'w-50' ,
        'method' => 'put'
    ])!!}


    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $category->name , ['class' => 'form-control' , 'placeholder' => 'Category Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection