@extends('admin.layouts.admin_layout')
@section('content')

<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fa fa-camera"></i> Cms Page Gallery 
            <a class="btn btn-success border_radius" href="{{ route('cms-pages.create') }}"><i class="fa fa-plus"></i> </a>
         </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{!!route('home')!!}">Home</a></li>
          <li class="breadcrumb-item "><a href="{!!route('cms-pages.index')!!}">Cms Page</a></li>
           <li class="breadcrumb-item"><a href="{{-- {!!route('cms-pages-gallery.index',$CmsPages->id)!!} --}}">Gallery</a></li>

        </ol>
      </div>
    </div>
    <hr/> 
   
{{--  here will be shown the listing of all data--}}
<div class="row">
  <div class="col-md-12">
    @include('admin.partials.flashes')
    <div class="card card-dark">
      <div class="card-header">
        <h4 class="card-title">
        <i class="fas fa-images"></i> Gallery </h4>
      </div>
      <div class="card-body">
        <table id="asdfasdf" class="table table-bordered table-hover table">
          <thead>
          <tr>
            <th width="5%">Sr.No.</th>
            <th width="20%">Gallery Title</th>
            <th width="20%">Description </th>
            <th width="10%">Status</th>           
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
                    <td>{!!$singlePage->name!!}</td>
                   
                   
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
            </table>
      </div>  
      {{--  {!! $CmsPages->render() !!}     --}}
    </div>  
    </div>
</div>
</div>
@section('js')
<!-- DataTables  & Plugins -->
{{-- <script src="{!!asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')!!}"></script> --}}
{{-- <script src="{!!asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/jszip/jszip.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/pdfmake/pdfmake.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/pdfmake/vfs_fonts.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/lugins/datatables-buttons/js/buttons.print.min.js')!!}"></script>
<script src="{!!asset('AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js')!!}"></script> --}}

@endsection
@endsection
