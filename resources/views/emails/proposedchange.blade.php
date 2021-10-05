@component('mail::message')
Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
 
<p>You proposed a change for {{ $data['type']}} : <b>{{ $data['object']}}</b> @component('mail::button', ['url' => $data['link']])
Click Here To View
@endcomponent</p>

<p>Thank you for your submittal to Canonizer.com.</p>
     
@component('mail::button', ['url' => url('/') . '/' . $link])
Click Here To View History
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent

