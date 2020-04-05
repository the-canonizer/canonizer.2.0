 @component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
<p>
 
{{ $data['nick_name'] }} has proposed a change to this {{$data['type']}}<a href="{{ url('/') . '/' . $link }}">{{ $data['object']}} </a> which you currently {{(isset($data['subscriber']) && $data['subscriber'] == 1) ? 'subscribed' :'directly support'}}.  If no supporters of this {{$data['typeobject']}} object to this change, it will go live in {{ config('app.go_live_text') }}.
<p>Edit summary : {{ $data['note'] }}</p>
</p>

@if(isset($data['subscriber']) && $data['subscriber'] == 1)
<h4>You are receiving this e-mail because:</h4>
<p>
	<ul>
	<li>You are subscribed to <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a></li>
</ul>
</p>
@else
	<h4>You are receiving this e-mail because:</h4>
	<p>
		<ul>
			<li>You are directly supporting <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a></li>
		</ul>
	</p>
 <h4>Note:</h4>
	<p>
	 We request that all <b>direct</b> supporters of a camp continue to receive notifications and take responsibility for the camp. You can avoid being notified by <b>delegating</b> your support to someone else.
	</p>
@endif

Sincerely,<br>
{{ config('app.email_signature') }}
 

@endcomponent

