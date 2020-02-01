@inject('model' , 'App\Models\Setting')
@extends('admin.master')

@section('title')
    Settings
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Settings</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Settings</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')

{!!Form::model($model , [
    'method' => 'put' , 
    'route' => 'setting.update' ,
    'class' => 'mx-5 my-3'
])!!}

    <div class = "form-group">
      {!!Form::label('commission' , 'Commission "You should enter a value between 0.01 to 0.99" ')!!}
      {!!Form::number('commission' , $settings->commission , ['placeholder' => 'Commission' , 'class' => 'form-control' , 'step' => '0.01' , 'max' => '0.99' , 'min' => '0'])!!}
    </div>

    <div class = "form-group">
      {!!Form::label('commission' , 'Commission Text')!!}
      {!!Form::textarea('commission_text' , $settings->commission_text , ['placeholder' => 'Commission Text' , 'class' => 'form-control' , 'rows' => '4'])!!}
    </div>

    <div class = "form-group">
      {!!Form::label('banks_text' , 'Banks text')!!}
      {!!Form::textarea('banks_text' , $settings->banks_text , ['placeholder' => 'banks text' , 'class' => 'form-control'  , 'rows' => '4'])!!}
    </div>

    <div class = "form-group">
      {!!Form::label('max_orders_amount ' , 'Maximum number of orders amount')!!}
      {!!Form::number('max_orders_amount' , $settings->max_orders_amount , ['placeholder' => 'Maximum orders amount for resturants' , 'class' => 'form-control' , 'step' => '0.01' , 'min' => '0'])!!}
    </div>

    <div class = "form-group">
          {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

  {!!Form::close()!!}
    

@endsection