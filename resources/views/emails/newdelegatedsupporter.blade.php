@component('mail::message')
 Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 @if (isset($data['support_added']) && $data['support_added'] == 1 )
 	{{ $data['nick_name']}} has just added their support to this camp: <a href="{{$link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1 && $data['delegate_support_deleted'] != 1)
 {{ $data['nick_name']}} has just removed their support from this camp: <a href="{{ $link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1 && $data['delegate_support_deleted'] == 1)
 {{ $data['nick_name']}} has just removed their delegated support from {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this camp: <a href="{{ $link }}"><b>{{ $data['object']}}</b></a>
 @else
 	{{ $data['nick_name']}} has just delegated their support to {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this camp: <a href="{{ $link }}"><b>{{ $data['object']}}</b></a>
 @endif

 </p>

 <p>
@if(isset($data['subscriber']) && $data['subscriber'] == 1)
<h4>You are receiving this e-mail because:</h4>

	<ul>
		@if(isset($data['support_list']) && count($data['support_list']) > 0)
			@foreach($data['support_list'] as $support)
		 	<li>You are subscribed to {!!$support!!}</li>
		 @endforeach
		@else
			<li>You are subscribed to <a href="{{ $link }}">{{ $data['support_camp']}} </a></li>
		@endif	
	
</ul>

@else
	<h4>You are receiving this e-mail because:</h4>
		<ul>
			@if(isset($data['support_list']) && count($data['support_list']) > 0)
			@foreach($data['support_list'] as $support)
			 	<li>You are directly supporting {!!$support!!}</li>
			 @endforeach
			
			@else
			<li>You are directly supporting <a href="{{ $link }}">{{ $data['support_camp']}} </a></li>
			@endif

			@if(isset($data['also_subscriber']) && $data['also_subscriber'] == 1 && isset($data['sub_support_list']) && count($data['sub_support_list']) > 0)
			@foreach($data['sub_support_list'] as $support)
			 	<li>You are subscribed to {!!$support!!}</li>
			 @endforeach
			@endif

		</ul>
   <h4>Note:</h4>
	<p>
	 We request that all <b>direct</b> supporters of a camp continue to receive notifications and take responsibility for the camp. You can avoid being notified by <b>delegating</b> your support to someone else.
	</p>
@endif
</p>
@component('mail::button', ['url' => $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent