@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 @if (isset($data['support_added']) && $data['support_added'] == 1 )
 	{{ $data['nick_name']}} has just added their support to this camp: <a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1)
 {{ $data['nick_name']}} has just removed their support from this camp: <a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @else
 	{{ $data['nick_name']}} has just delegated their support to {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic:<a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @endif

 </p>

 <p>
@if(isset($data['subscriber']) && $data['subscriber'] == 1)
<h4>You are receiving this e-mail because:</h4>

	<ul>
		@if(isset($data['support_list']) && $data['support_list']!='')
			<li>You are subscribed to {!!$data['support_list']!!}</li>
		@else
			<li>You are subscribed to <a href="{{ url('/') . '/' . $link }}">{{ $data['object']}} </a></li>
		@endif	
	
</ul>

@else
	<h4>You are receiving this e-mail because:</h4>
		<ul>
			@if(isset($data['support_list']) && $data['support_list']!='')
			<li>You are directly supporting {!!$data['support_list']!!}</li>
			@else
			<li>You are directly supporting <a href="{{ url('/') . '/' . $link }}">{{ $data['object']}} </a></li>
			@endif
			
		</ul>
   <h4>Note:</h4>
	<p>
	 We request that all <b>direct</b> supporters of a camp continue to receive notifications and take responsibility for the camp. You can avoid being notified by <b>delegating</b> your support to someone else.
	</p>
@endif
</p>
@component('mail::button', ['url' => url('/') . '/' . $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent