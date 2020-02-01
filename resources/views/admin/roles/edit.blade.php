@extends('admin.master')
@inject('model' , 'App\Role')

@section('title')
    Edit role 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit role</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('role.index'))}}">roles</a></li>
            <li class="breadcrumb-item active">edit role</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
  @include('admin.includes.flash-message')

    {!!Form::model($model , [
        'route'  => ['role.update' , $role->id] ,
        'class'  => 'w-100 px-5 py-3' ,
        'method' => 'put'
    ])!!}

    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $role->name , ['class' => 'form-control' , 'placeholder' => 'Role Name'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('display_name', 'Display name') !!}
            {!!Form::text('display_name', $role->display_name , ['class' => 'form-control' , 'placeholder' => 'Display name'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('description', 'Description') !!}
            {!!Form::text('description', $role->description , ['class' => 'form-control' , 'placeholder' => 'Description'])!!}
    </div>


    <div class="form-group">
        {!!Form::label('form-check', 'List of permissions') !!}
        <br>
        
        <div class = "form-check col-sm-4 custom-control custom-checkbox">
            {!!Form::checkbox(null , null , false  , ['class'  => 'custom-control-input' ,'id' => 'select-all-permissions'])!!}
            {!!Form::label('select-all-permissions' ,  'Check all' , ['class' => 'custom-control-label'])!!}     
        </div>
        <br><br>
      <div class = "row">

        @inject('permissions' , 'App\Permission')
        
        @foreach($permissions->all() as $permission)
          <div class = "form-check col-sm-4 custom-control custom-checkbox">
              {!!Form::checkbox('permissions[]' , $permission->id , $role->hasPermission($permission->name)  ? true : false  , ['class'  => 'custom-control-input perm' ,'id' => 'check' . $permission->id])!!}
              {!!Form::label('check' . $permission->id ,   $permission->display_name, ['class' => 'custom-control-label'])!!}     
          </div>
        @endforeach
        
      </div>
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

    @push('script')
        <script>        
          $(document).ready(function()
          {
              $("#select-all-permissions").click(function () {
                  $('.perm').not(this).prop('checked', this.checked);
              });
          });
        </script>
        
    @endpush

@endsection