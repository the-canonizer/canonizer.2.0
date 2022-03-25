@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible"> 
                <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="closeAlert(this)">&times;</a>
                {!! session('success') !!}
            </div>
        @endif
        
        
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Shares</div>
            <div class="panel-options">
                    <a href="{{ url('/admin/shares/create') }}" data-rel="collapse"><i class="fa fa-plus"></i> Add Share</a>
            </div>

        </div>
        <div class="content-box-large box-with-header">
            <div class="row">
                <div class="form-group">
                    <!-- <label class="forl-label">Month</label> -->
                   
                    <select id="selectMonth" class="form-control" onChange="changeMonthData()">
                        <option value="">Select Month</option>
                            <?php

                                    foreach($month_range as $m){
                                       if(isset($_REQUEST['month']) && $_REQUEST['month'] !='' && date('Y-m-d', strtotime($m)) ==  date('Y-m-d', strtotime($_REQUEST['month'])) ){
                                         echo '<option value="'.date('Y-m-d', strtotime($m)).'" selected="selected">'.date('F,Y', strtotime($m)).'</option>';
                                       }else{
                                           echo '<option value="'.date('Y-m-d', strtotime($m)).'">'.date('F,Y', strtotime($m)).'</option>';

                                       }
                                     }
                            ?>
                    </select>
                </div>
            </div>
            <div id="data_shares">
                <table class="table table-row">
                <tr>
                    <th>Nick Name</th>
                    <th>Date</th>
                    <th>share</th>
                    <th>Sqrt(shares)</th>
                    <th>Action</th>
                </tr>
                @if(isset($shares) && count($shares) > 0)
                @foreach($shares as $share)
                <tr>
                    <td><a href="{{route('user_supports',$share->usernickname->id)}}">{{ $share->usernickname->nick_name }}</a></td>
                    <td>{{ date("d F,Y",strtotime($share->as_of_date)) }}</td>
                    <td>{{ $share->share_value}}</td>
                    <td>{{ number_format(sqrt($share->share_value),2)}}</td>
                    <td>
                        <a href="{{ url('/admin/shares/edit/'.$share->id) }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>
                        &nbsp;&nbsp;<a href="javascript:void(0)" onClick='deleteShare("{{$share->id }}")'><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="5"><span>No Share data found!</span></td></tr>                
                @endif
            </table>
            @if (isset($_REQUEST['month']) && $_REQUEST['month'] !='')
              {{ $shares->appends(['month'=>$_REQUEST['month']])->links() }}
            @else
              {{ $shares->links() }}
            @endif
            
            </div>
            

        </div>
    </div>
</div>
<script>
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    function changeMonthData(){

        var val = $('#selectMonth option:selected').val();
        if(val == ''){
            var uri = window.location.toString();
            let page = getParameterByName('page',uri);
            if (uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
                window.history.replaceState({}, document.title, clean_uri+"?page="+page);            
            }
        }else{
            var uri = window.location.toString();
            let page = getParameterByName('page',uri) || 1;     
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri+"?month="+val+"&page="+page);     
           
        }
        var csrf_token = "<?php echo csrf_token(); ?>";
        $.ajax({
            url:"<?php echo url('/admin/shares/getshares'); ?>",
            method:'post',
            data:{month:val,_token:csrf_token},
            success:function(response){
                    $('#data_shares').html(response);
            }
        })
    }
   function deleteShare(id){
     var delete_url = "<?php echo url('/admin/shares/delete') ?>/"+id;
       var check = confirm("Are you sure to delete this record?");
        if(check == true){
            window.location.href = delete_url;
        }else{
            console.log('no')
        }
    }

   function closeAlert(e){
      $(e).parents('div.alert').remove();
   }

</script>
@endsection