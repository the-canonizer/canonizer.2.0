@component('mail::message')
 Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 You({{ $data['nick_name']}}) have just removed your delegated support from {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic: <a href="{{ $link }}"><b>{{ $data['topic']->topic_name}}</b></a>
</p>
Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent