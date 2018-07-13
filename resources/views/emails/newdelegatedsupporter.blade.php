@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 {{ $data['nick_name']}} has just delegated their support to you in this topic:

 </p>
@component('mail::button', ['url' => url('/') . '/' . $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent