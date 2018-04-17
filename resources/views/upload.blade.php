@extends('layouts.app')
@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif
      	
<div class="right-whitePnl">
    <div class="container-fluid">
        <div class="Gcolor-Pnl">
            <h3>Upload Images / Document Files</h3>
            <div class="content">	  
			   <form method="post" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <input class="form-control" name="file" type="file"/>
                        
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="file_name" name="file_name" placeholder="File Name" type="text"/>
                       
                    </div>
               
                    <button id="upload_file" class="btn btn-sm btn-primary">Upload</button>
               </form>
               <div  style="margin-top:10px;background:#fff;">
               <table class="table table-striped">
                <tr><th>File Name</th><th>Short Code</th><th style="width:20%">Uploaded Date </th></tr>
                @foreach($uploaded as $upload)
                    <tr><td style="word-break:break-all">{{ $upload->file_name }} &nbsp;&nbsp;&nbsp;<a target="_blank" href="{{ url('files/'.$upload->file_name) }}"><i class="fa fa-external-link"></i></a></td>
                    <td style="word-break:break-all">{{ $upload->getShortCode() }}</td>
                    <td>{{ $upload->created_at }}</td></tr>
                @endforeach
               </table>
               </div>
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->

@endsection
 