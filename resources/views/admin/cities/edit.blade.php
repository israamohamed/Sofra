@extends('admin.master')
@inject('model' , 'App\Models\City')

@section('title')
    Edit city 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit city</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('city.index'))}}">cities</a></li>
            <li class="breadcrumb-item active">edit city</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
    @include('admin.includes.flash-message')

    {!!Form::model($model , [
        'route'  => ['city.update' , $city->id] ,
        'class'  => 'w-50' ,
        'method' => 'put'
    ])!!}


    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $city->name , ['class' => 'form-control' , 'placeholder' => 'City Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection