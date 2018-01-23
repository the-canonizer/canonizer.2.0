@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Nicknames</h1>
</div> 

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
   <div class="row justify-content-between">
    <div class="col-sm-12 margin-btm-2">
        <div class="well">
            <ul class="nav prfl_ul">
                <li><a class="" href="{{ route('settings')}}">Manage Profile info</a></li>
                <li class="active"><a class="" href="{{ route('settings.nickname')}}" >Add & Manage Nick Names</a></li>
				<li class=""><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
            </ul>

         <!-- exsisting Nick Names -->
         <div class="">
           <table class="table">
           <thead class="thead-default">
              <tr>
                <th>S.No</th>
                <th>Nick Name</th>
                <th>Visibilty Status</th>
                <!--th>Manage Actions</th-->
              </tr>
		   </thead>
           <tbody>
                 @foreach($nicknames as $k=>$nickname)
                   <tr>
                       <th scope="row">{{ $k+1 }}</th>
                       <td>{{ $nickname->nick_name}}</td>
                       <td>{{ ($nickname->private == 1) ? 'Private' : 'Public'}}</td>
                       <!--td>
                          <a href="">Edit</a>
                          <a href=""><i class="fa fa-trash" aria-hidden="true"></i></a>
                       </td-->
                   </tr>
                 @endforeach
              </tbody>
           </table>

         </div>



            <div id="myTabContent" class="add-nickname-section">  
                 <h5>Add New Nick Name </h5>
                <form action="{{ route('settings.nickname.add')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
                            <label for="nick_name">Add Nick Name</label>
                            <input type="text" name="nick_name" class="form-control" id="" value="{{ old('nick_name')}}">
                            @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
                        </div>
                        <div class="col-sm-6 margin-btm-1">
                            <label for="namespace">Visibility status</label>
                            <select class="form-control" name="private">
                                <option value="0">Public</option>
                                <option vlaue="1">Private</option>
                            </select>
                            @if ($errors->has('private')) <p class="help-block">{{ $errors->first('private') }}</p> @endif
                        </div> 
                    </div>
                    
                    <button type="submit" class="btn btn-login">Create</button>
                    <button type="submit" class="btn btn-default">Cancel</button>
                </form>  
        </div>
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
        $(document).ready(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

