@component('mail::message')
<p>Hello  {{ $user->first_name }} {{ $user->last_name }}, </p>

<p>
    {{ $data['nick_name']->nick_name }} {{$data['post_type']}} the following post to the Camp
    <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a> forum:

</p>

<h4>Thread Title: </h4>
<p> <a href="{{ url('/').'/'.$link }}">{{ $data['thread'][0]->title }}</a> </p>

<h4>Post: </h4>

<p> {!! $data['post'] !!}. </p>

<p>
@if(isset($data['subscriber']) && $data['subscriber'] == 1)
<h4>You are receiving this e-mail because:</h4>
	<ul>
		@if(isset($data['support_list']) && count($data['support_list']) > 0)
			@foreach($data['support_list'] as $support)
		 	<li>You are subscribed to {!!$support!!}</li>
		 @endforeach
		@else
			<li>You are subscribed to <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a></li>
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
			<li>You are directly supporting <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a></li>
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
<p>  Sincerely, </p>
<p> {{ config('app.email_signature') }}</p>
@endcomponent
