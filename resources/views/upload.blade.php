@extends('layouts.app')
@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger errorMsg">
            <strong>Error! </strong>{{ Session::get('error') }}
        </div>
    @else

        @if ($errors->has('file'))
            <div class="alert alert-danger errorMsg">
                <strong>Error! </strong>{{ $errors->first('file') }}
            </div>
        @endif
        @if ($errors->has('file_name'))
            <div class="alert alert-danger errorMsg">
                <strong>Error! </strong>{{ $errors->first('file_name') }}
            </div>
        @endif

    @endif

    @if (Session::has('success'))
        <div class="alert alert-success" id="successMsg">
            <strong>Success! </strong>{{ Session::get('success') }}
        </div>
    @endif

    <div class="alert alert-danger" id="fileNameErrorBox" style="display: none;"></div>

    <div class="right-whitePnl">
        <div class="container-fluid">
            <div class="Gcolor-Pnl">
                <!-- <h3>Upload images only ( jpeg,bmp,png,jpg,gif ), Max size 5 MB</h3> -->
                <h3>Upload Files, Max size 5 MB</h3>
                <div class="content">
                    <form method="post" id="uploadForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <div class="custom-file w-100">
                                <input class="custom-file-input w-100" id="file" name="file" type="file" />
                                <label for="file" class="custom-file-control text-nowrap overflow-hidden">Choose File...</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" onkeydown="restrictTextField(event,200)" id="file_name"
                                name="file_name" placeholder="File Name (with no extension)" type="text" />

                        </div>
                        <p style="color:red">Warning : Once a file will be uploaded there is no way to delete the file.</p>
                        <button id="upload_file" class="btn btn-sm btn-primary">Upload</button>
                    </form>
                    <div class="mt-3 bg-white table-responsive">
                        <table class="table table-striped">
                            <tr class="text-nowrap">
                                <th>File Name</th>
                                <th>Short Code</th>
                                <th style="width:30%">Uploaded Date </th>
                            </tr>
                            @foreach ($uploaded as $upload)
                                <tr>
                                    <td style="word-break:break-all">{{ $upload->file_name }} &nbsp;&nbsp;&nbsp;<a
                                            target="_blank" href="{{ url('files/' . $upload->file_name) }}"><i
                                                title="View file" class="fa fa-external-link"></i></a></td>
                                    <td style="word-break:break-all">{{ $upload->getShortCode() }}</td>
                                    <td>{{ to_local_time($upload->created_at) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid-->
    </div> <!-- /.right-whitePnl-->


    <script>
        var format = /[`!@#$%^&*() +\=\[\]{};':"\\|,.<>\/?~]/;

        function validateFileName(fileName) {
            return format.test(fileName)
        }

        $("#uploadForm").submit(function() {

            fileName = $("#file_name").val();

            if ($('#file')[0].files.length == 0) {

                $("#fileNameErrorBox").html('<strong>Error! </strong> The file field is required');
                $("#fileNameErrorBox").css("display", "block");
                $("#successMsg").css("display", "none");
                $(".errorMsg").css("display", "none");

                return false;
            }

            if (fileName == '' || fileName == NaN || fileName == undefined) {

                $("#fileNameErrorBox").html('<strong>Error! </strong> File name is required');
                $("#fileNameErrorBox").css("display", "block");
                $("#successMsg").css("display", "none");
                $(".errorMsg").css("display", "none");

                return false;
            }
            
            var res = validateFileName(fileName);

            if (res) {

                $("#fileNameErrorBox").html(
                    '<strong>Error! </strong> Special characters are not allowed in file name field');
                $("#fileNameErrorBox").css("display", "block");
                $("#successMsg").css("display", "none");
                $(".errorMsg").css("display", "none");
                return false
                
            } else {
                $("#fileNameErrorBox").css("display", "none");
                return true
            }

        });

        $(document).ready(function() {
            $('input[type="file"]').change(function(event) {
                var _size = this.files[0].size;
                $(this).next(".custom-file-control").html(this.files[0].name);
                //var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
                //i=0;while(_size>900){_size/=1024;i++;}
                //var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
                console.log('FILE SIZE = ',_size);
                if(_size> 5242880){
                    $("#fileNameErrorBox").html('<strong>Error! </strong> The file may not be greater than 5 MB');
                    $("#fileNameErrorBox").css("display", "block");
                    $("#successMsg").css("display", "none");
                    $(".errorMsg").css("display", "none");
                    $("#file").val('');
                    return false;
                }
                else{
                    $("#fileNameErrorBox").css("display", "none");
                }
            });
        });

    </script>

@endsection
