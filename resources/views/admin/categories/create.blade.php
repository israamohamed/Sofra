@extends('admin.master')
@inject('model' , 'App\Models\Category')

@section('title')
    New Category 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New Category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('category.index'))}}">categories</a></li>
            <li class="breadcrumb-item active">new category</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

    {!!Form::model($model , [
        'route'  => 'category.store' ,
        'class'  => 'w-100 px-5 py-3'
    ])!!}

    @include('admin.includes.flash-message')
    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', null , ['class' => 'form-control' , 'placeholder' => 'Category Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Add' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection