@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
{{ $data['nick_name']}} has proposed a change to this {{$data['type']}} <a href="{{ url('/') . '/' . $link }}">{{ $data['object']}} </a> which you currently directly support.  If no supporters of this {{$data['type']}} object to this change, it will go live in one week {{ $data['go_live_time'] }}.
</p>

<p>
If you do not wish to receive these notifications, you can either delegate your support to some other camp supporter in the topic, or remove your support from the camp.  We request that all direct supporters of a camp continue to receive and take some responsibility for the camp.

</p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent