@extends('admin.master')
@inject('model' , 'App\Models\Payment')

@section('title')
    Edit Payment 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Payment</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('payment.index'))}}">categories</a></li>
            <li class="breadcrumb-item active">edit payment</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
    @include('admin.includes.validation_errors')

    {!!Form::model($model , [
        'route'  => ['payment.update' , $payment->id] ,
        'class'  => 'w-100 px-5 py-3' ,
        'method' => 'put'
    ])!!}


    <div class = "form-group">
            {!!Form::label('restaurant_id', 'Restaurant') !!}
            @inject('restaurants' , 'App\Models\Restaurant')
            {!!Form::select('restaurant_id', $restaurants->all()->pluck('name' , 'id')->toArray() , $payment->restaurant->id , ['class' => 'form-control select2 ' , 'style' => 'width:100%;' , 'placeholder' => 'Choose Restaurant'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('paid', 'Amount') !!}
            {!!Form::number('paid', $payment->paid , ['class' => 'form-control' , 'placeholder' => 'Amount'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('notes', 'Notes') !!}
            {!!Form::textarea('notes', $payment->notes , ['class' => 'form-control' , 'placeholder' => 'Notes .. '])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('date', 'Date') !!}
            {!!Form::date('date', $payment->date , ['class' => 'form-control' , 'placeholder' => 'Date '])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}
    
    @push('script')
        <script> 
          $('.select2').select2({
            theme: 'bootstrap4'
          })
        </script>
    @endpush

@endsection