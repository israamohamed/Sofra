@extends('admin.master')

@section('title')
    contacts 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>contacts</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">contacts</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
@if(count($contacts) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
                <div class="card-header bg-primary">
                   
                    <div class="card-tools w-50 m-auto float-none">
                        <div>
                        {!!Form::open([
                    
                        'method'=> 'GET' , 
                        'class' => 'input-group input-group-sm' 
                        ])!!}
                        
                            {!!Form::select('type' , [1 => 'Complaint' , 2 => 'Suggestion' , 3 => 'Enquiry' ] ,  null , ['class' => 'form-control message-type' , 'placeholder' => 'Message Type' ])!!}
                            
                            {!!Form::text('search' ,  null , ['class' => 'form-control' , 'placeholder' => 'Search' ])!!}
            
                        <div class="input-group-append">
                            {!!Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-default'] )  !!}
                        </div>
                        {!!Form::close()!!}
                        </div>
                    </div>
                </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class = "text-center">Created At</th>
                    <th class = "text-center">Name</th>
                    <th class = "text-center">Email</th>
                    <th class = "text-center">Phone</th>
                    <th class = "text-center">Messsage</th>
                    <th class = "text-center">Type</th>  
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$contact->created_at}}</td>
                            <td class = "text-center">{{$contact->name}}</td>
                            <td class = "text-center">{{$contact->email}}</td>
                            <td class = "text-center">{{$contact->phone}}</td>
                            <td class = "text-center"><button id = "{{$contact->message}}" data-email = "{{$contact->email}}" class = "btn btn-primary show-message" data-toggle="modal" data-target="#modal-primary">Show Message</button></td>
                            
                            <td class = "text-center">{{$contact->type==1 ? 'Complaint' : ($contact->type == 2 ? 'Suggestion' : 'Enquiry')}}</td>
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_contact') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['contact.destroy' , $contact->id ] , 
                                    'method' => 'delete'
                                ]) !!}
                                
                                
                                {!!Form::close()!!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
                <div class="modal-content bg-primary">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>--</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
        </div>
    </div>

    {{$contacts->links()}}

@else 
    <p>No contacts available</p> 
@endif
@push('script')
    <script>
        $('.show-message').click(function(){
            $('.modal .modal-body p').text($(this).attr('id'));
            $('.modal .modal-title').text('Mail : ' + $(this).data("email"));
        });

        /*$('.message-type').select2();*/
    </script>
@endpush

@endsection