@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>Thank you for your submittal to Canonizer.com.</p>
     
@component('mail::button', ['url' => url('/') . '/' . $link])
Click Here To View History
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent

