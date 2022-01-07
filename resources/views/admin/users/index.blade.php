@extends('admin.layouts.admin_layout')
@section('content')

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Users Management
        <a class="btn btn-success" href="{{ route('users.create') }}" style="border-radius: 50%;"><i class="fa fa-plus"></i> </a>
      </h1>
      </div>

      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{!!route('home')!!}">Home</a></li>
          <li class="breadcrumb-item active"><a href="{{route('users.index')}}">Users</a></li>
        </ol>
      </div>
    </div>
    @yield('content')
  </div><!-- /.container-fluid -->

@if ($message = Session::get('success'))

<div class="alert alert-success">

  <p>{{ $message }}</p>

</div>

@endif


<table class="table table-bordered">

 <tr>

   <th>No</th>

   <th>Name</th>

   <th>Email</th>

   <th>Roles</th>

   <th width="280px">Action</th>

 </tr>

 @foreach ($data as $key => $user)

  <tr>

    <td>{{ ++$i }}</td>

    <td>{{ $user->name }}</td>

    <td>{{ $user->email }}</td>

    <td>


      @if(!empty($user->getRoleNames()))

        @foreach($user->getRoleNames() as $v)

          <strong>{!!$v !!}</strong> 

        @endforeach

      @endif

    </td>

    <td>

       <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>

       <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>

        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}

            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

        {!! Form::close() !!}

    </td>

  </tr>
 @endforeach
</table>
{!! $data->render() !!}
@endsection