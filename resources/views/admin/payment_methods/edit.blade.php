@extends('admin.master')
@inject('model' , 'App\Models\PaymentMethod')

@section('title')
    Edit Payment Method 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Payment method</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('paymentMethod.index'))}}">payment methods</a></li>
            <li class="breadcrumb-item active">edit payment method</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
    @include('admin.includes.flash-message')

    {!!Form::model($model , [
        'route'  => ['paymentMethod.update' , $payment_method->id] ,
        'class'  => 'w-50' ,
        'method' => 'put'
    ])!!}


    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $payment_method->name , ['class' => 'form-control' , 'placeholder' => 'payment method Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection