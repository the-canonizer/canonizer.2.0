@extends('layouts.app')
@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@else
	
@if ($errors->has('file')) 
<div class="alert alert-danger">
    <strong>Error! </strong>{{ $errors->first('file') }}  
</div>
@endif  

<div class="alert alert-danger" id="fileNameErrorBox" style="display: none;">
   
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success" id="successMsg">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif
   
<div class="right-whitePnl">
    <div class="container-fluid">
        <div class="Gcolor-Pnl">
            <!-- <h3>Upload images only ( jpeg,bmp,png,jpg,gif ), Max size 5 MB</h3> -->
            <h3>Upload Files, Max size 5 MB</h3>
            <div class="content">	  
			   <form method="post" id="uploadForm" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <input class="form-control" name="file" type="file"/>
                        
                    </div>
                    <div class="form-group">
                        <input class="form-control"  onkeydown="restrictTextField(event,200)" id="file_name" name="file_name" placeholder="File Name (with no extension)" type="text"/>
                       
                    </div>
                    <p style="color:red">Warning : Once a file will be uploaded there is no way to delete the file.</p>
                    <button id="upload_file"  class="btn btn-sm btn-primary">Upload</button>
               </form>
               <div  style="margin-top:10px;background:#fff;">
               <table class="table table-striped">
                <tr><th>File Name</th><th>Short Code</th><th style="width:20%">Uploaded Date </th></tr>
                @foreach($uploaded as $upload)
                    <tr><td style="word-break:break-all">{{ $upload->file_name }} &nbsp;&nbsp;&nbsp;<a target="_blank" href="{{ url('files/'.$upload->file_name) }}"><i title="View file" class="fa fa-external-link"></i></a></td>
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


<script>

var format = /[`!@#$%^&*()+\=\[\]{};':"\\|,.<>\/?~]/;

 function validateFileName(fileName){
    return format.test(fileName)
 }

$("#uploadForm").submit(function(){
    fileName = $("#file_name").val();
    if(fileName == ''){
        return true;
    }

    var res = validateFileName(fileName);

    if(res){
        $("#fileNameErrorBox").html('<strong>Error! </strong> Special characters are not allowed in Name field');
        $("#fileNameErrorBox").css("display", "block");
        $("#successMsg").css("display", "none");
         return false
    }else{
        $("#fileNameErrorBox").css("display", "none");
        return true
    }
  
});

</script>

@endsection
 