@extends('admin.master')
@inject('model' , 'App\User')

@section('title')
    Edit user
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit user</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('user.index'))}}">users</a></li>
            <li class="breadcrumb-item active">Edit user</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

    {!!Form::model($model , [
        'route'  => ['user.update' , $user->id] ,
        'class'  => 'w-100 px-5 py-3' , 
        'method' => 'put'
    ])!!}

    @include('admin.includes.flash-message')
    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $user->name , ['class' => 'form-control' , 'placeholder' => 'User Name'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::email('email', $user->email , ['class' => 'form-control' , 'placeholder' => 'User Email'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('password', 'Password') !!}
            {!!Form::password('password' , ['class' => 'form-control' , 'placeholder' => 'User Password'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('password_confirmation', 'Password Confirm') !!}
            {!!Form::password('password_confirmation' , ['class' => 'form-control' , 'placeholder' => 'Password Confirmation'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('roles', 'User roles') !!}
            @inject('roles' , 'App\Role')
            <div class="select2-purple">
              {!!Form::select('roles[]' , $roles->all()->pluck('name' , 'id')->toArray() , $user->roles , ['class' => 'roles select2 select2-hidden-accessible ' , 'style' => 'width: 100%;' ,  'data-placeholder' => 'Select user roles' , 'data-dropdown-css-class' =>  'select2-purple' , 'multiple'])!!}
            </div>
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}


    @push('script')
      <script> 
        $('.roles').select2()
      </script>
    @endpush

@endsection