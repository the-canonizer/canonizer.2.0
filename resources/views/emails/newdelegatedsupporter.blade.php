@component('mail::message')
 Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 @if (isset($data['support_added']) && $data['support_added'] == 1 )
 	{{ $data['nick_name']}} has just added their support to this camp: <a href="{{$link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1 && $data['delegate_support_deleted'] != 1)
 {{ $data['nick_name']}} has just removed their support from this camp: <a href="{{ $link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1 && $data['delegate_support_deleted'] == 1)
 {{ $data['nick_name']}} has just removed their delegated support from {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic: <a href="{{ $link }}"><b>{{ $data['topic']->topic_name}}</b></a>
 @else
 <a target="_blank" href="<?= route('user_supports',$data['nick_name_id']) .'?topicnum=&campnum=&namespace=' . $data['namespace_id']; ?>">{{ $data['nick_name']}} </a> has just delegated their support to {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic: <a href="{{ $link }}"><b>{{ $data['object']}}</b></a>
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
		We request that all <b>direct</b> supporters of a camp continue to receive notifications and take responsibility for the camp. If you <b>delegate</b> your support to someone else, you will no longer receive these notifications. <b>Delegating</b> your support to someone else will also result in your support following them for all camps in this topic.
	</p>
@endif
</p>
@component('mail::button', ['url' => $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent