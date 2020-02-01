@extends('admin.master')
@inject('model' , 'App\Models\Payment')

@section('title')
    New payment 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New payment</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('payment.index'))}}">payments</a></li>
            <li class="breadcrumb-item active">new payment</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

    {!!Form::model($model , [
        'route'  => 'payment.store' ,
        'class'  => 'w-100 px-5 py-3'
    ])!!}

    @include('admin.includes.flash-message')
    <div class = "form-group">
            {!!Form::label('restaurant_id', 'Restaurant') !!}
            @inject('restaurants' , 'App\Models\Restaurant')
            {!!Form::select('restaurant_id', $restaurants->all()->pluck('name' , 'id')->toArray() , old('restaurant_id') , ['class' => 'form-control select2 ' , 'style' => 'width:100%;' , 'placeholder' => 'Choose Restaurant'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('paid', 'Amount') !!}
            {!!Form::number('paid', old('amount') , ['class' => 'form-control' , 'placeholder' => 'Amount'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('notes', 'Notes') !!}
            {!!Form::textarea('notes', old('notes') , ['class' => 'form-control' , 'placeholder' => 'Notes .. ' , 'rows' => '3'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('date', 'Date') !!}
            {!!Form::date('date', old('date') , ['class' => 'form-control' , 'placeholder' => 'Date '])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Add' , ['class' => 'btn btn-primary'])!!}
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