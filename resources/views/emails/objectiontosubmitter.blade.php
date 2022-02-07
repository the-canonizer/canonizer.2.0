@component('mail::message')
 Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
<p>{{ $data['nick_name'] }} has objected to your <a href="{{ url('/') . '/' . $link }}" target='_balnk'>proposed change</a> submitted for {{$data['type']}} (<a href="{{$data['topic_link']}}">{{$data['object']}}</a>) {{$data['object_type']}} </p>
@if(isset($data['help_link']))
@component('mail::button', ['url' => url('/') . '/' . $data['help_link']])
See this link for options you can take when there are objections
@endcomponent
@endif
Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent