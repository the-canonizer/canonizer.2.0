@extends('layouts.app')
@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif
      	
<div class="right-whitePnl">
    <div class="container-fluid">
        <?php $user = $nickName->getUser();
            $privateFlags = explode(',',$user->private_flags);
            $supportedCamps = $nickName->getSupportCampList();
         ?>
         <address>
         @if(!in_array('last_name',$privateFlags) || !in_array('first_name',$privateFlags))
        <p><strong>Name : </strong>{{ !in_array('first_name',$privateFlags) ? $user->first_name : '' }} {{ !in_array('last_name',$privateFlags) ? $user->last_name : '' }}</p>
        @endif
        @if(!in_array('email',$privateFlags))
        <p><strong>Email : </strong>{{$user->email}}</p>
        @endif
        @if(!in_array('birthday',$privateFlags))
        <p><strong>Birthday : </strong>{{$user->birthday}}</p>
        @endif
        @if(!in_array('address_1',$privateFlags))
        <p><strong>Address : </strong>{{$user->address_1}}</p>
        @endif
        @if(!in_array('city',$privateFlags))
        <p><strong>City : </strong>{{$user->city}}</p>
        @endif
        @if(!in_array('state',$privateFlags))
        <p><strong>State : </strong>{{$user->state}}</p>
        @endif
        @if(!in_array('postal_code',$privateFlags))
        <p><strong>Postal Code : </strong>{{$user->postal_code}}</p>
        @endif
        @if(!in_array('country',$privateFlags))
        <p><strong>Country : </strong>{{$user->country}}</p>
        @endif
        </address>
        <div class="Gcolor-Pnl">
            <h3>Nick Name : {{ $nickName->nick_name}} 
            <div class="pull-right col-md-4">
            <form>
                <select onchange="submitForm(this)" name="namespace" id="namespace" class="namespace-select">
                    @foreach($namespaces as $namespace)
                        <option data-namespace="{{ $namespace->label }}" value="{{ $namespace->id }}" {{ isset($_REQUEST['namespace']) && $namespace->id == $_REQUEST['namespace'] ? 'selected' : ''}}>{{$namespace->label}}</option>
                    @endforeach
                </select>
                </form>
            </div>
            </h3>
            <div class="content">	  
            <h5>List of supported camps</h5>
            @if(count($supportedCamps) > 0)
			   @foreach($supportedCamps as $supports)
                <ul>
                    <li><a href="{{ $supports['link'] }}">{{$supports['title']}}</a></li>
                    <ul>
                        @if(isset($supports['array']))
                        @foreach($supports['array'] as $support_order)
                            @foreach($support_order as $support)
                                <li><a href="{{ $support['link'] }}">{{$support['title']}}</a></li>
                            @endforeach
                        @endforeach
                        @endif
                    </ul>
                </ul>
               @endforeach
            @else
                <p>No data available!</p>
            @endif
            
               
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->
<script>
function submitForm(element){
    $(element).parents('form').submit();
}
</script>
@endsection
 