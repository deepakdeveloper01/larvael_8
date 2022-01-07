@extends('admin.layouts.admin_layout')
@section('content')
@section('custom_css')
 <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')!!}">
  <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')!!}">
  <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')!!}">
@endsection  
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fa fa-file"></i> Cms Page Management 
            <a class="btn btn-success" href="{{ route('cms-pages.create') }}" style="border-radius: 50%;"><i class="fa fa-plus"></i> </a>
         </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{!!route('home')!!}">Home</a></li>
          <li class="breadcrumb-item active">Cms Page</li>
        </ol>
      </div>
    </div>
    <hr/> 
    <div class="row">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h4 class="card-title">
              <i class="fas fa-filter"></i> Filter</h4>
          </div>
          <form>
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-3">
                  <label for="exampleInputEmail1">Page Name </label>
                  <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Page name">
                </div>
                <div class="form-group col-md-3">
                  <label for="exampleInputPassword1">Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group col-md-3">
                      <label for="exampleInputPassword1">Status</label>
                      <select class="custom-select">
                            <option value="0">In Active</option>
                             <option value="1">Active</option>
                      </select>
                </div>
                <div class="form-group col-md-3" style="margin-top: 30px;">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-search"> </i>      Filter</button>
                  <button type="reset" class="btn btn-danger"><i class="fas fa-times"> </i> Reset</button>
                </div>
              </div>
            </div>
                <!-- /.card-body -->

               {{--  <div class="card-footer">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-search"> </i>      Filter</button>
                   <button type="reset" class="btn btn-danger"><i class="fas fa-times"> </i>      Reset</button>
                </div> --}}
          </form>
        </div>
      </div>
    </div>

{{--  here will be shown the listing of all data--}}
<div class="row">
  <div class="col-md-12">
    @include('admin.partials.flashes')
    <div class="card card-dark">
      <div class="card-header">
        <h4 class="card-title">
        <i class="fas fa-list"></i> Records </h4>
      </div>
      <div class="card-body">
        <table id="example2" class="table table-bordered table-hover table">
          <thead>
          <tr>
            <th width="5%">Sr.No.</th>
            <th width="20%">Page Title</th>
            <th width="10%">Platform(s)</th>
           
            <th width="20%">Description </th>
            <th width="10%">Created</th>
             <th width="35%"> Action</th>
          </tr>
          </thead>
          <tbody>
            @if(!empty($CmsPages))
                 @foreach($CmsPages as $key => $singlePage)
                  <tr>
                    <td>{!!$key+1!!}</td>
                    <td>{!!$singlePage->name!!}</td>
                    <td>{!!$singlePage->slug!!}</td>
                   
                    <td><p><strong>In Short:-</strong><span>{!!$singlePage->short_description!!}</span></p>{!!$singlePage->description!!}</td>
                    <td>{!!date('Y-m-d h:i A',strtotime($singlePage->created_at))!!} 
                      <br/> 
                      <strong>{!!date('Y-m-d h:i A',strtotime($singlePage->updated_at))!!}</strong>
                    </td>
                    <td>
                      <a href="{!! route('cms-pages.show', $singlePage->id) !!}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                      <a href="{!! route('cms-pages.edit', $singlePage->id) !!}" class="btn btn-default btn-sm"><i class="fas fa-edit"></i></a>
                       @include('admin.partials.delete_modal', ['data' => $singlePage, 'name' => 'cms-pages'])
                    </td>

                  </tr>
                 @endforeach
                 @endif 
                  </tbody>
                  {{-- <tfoot>
                  <tr>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                  </tr>
                  </tfoot> --}}
                </table>
      </div>  
    </div>  
    </div>
</div>
</div>
@section('js')
<!-- DataTables  & Plugins -->
<script src="{!!asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')!!}"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
      "columnDefs": [{ "orderable": false, "targets": -1 }
]
    });
  });

</script>
<!-- Admi
@endsection
@endsection
