@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 @if (isset($data['support_added']) && $data['support_added'] == 1 )
 	{{ $data['nick_name']}} has just added their support to this topic: <b>{{ $data['object']}}</b>
 @elseif(isset($data['support_added']) && $data['support_deleted'] == 1)
 {{ $data['nick_name']}} has just added their support to this topic: <b>{{ $data['object']}}</b>
 @else
 	{{ $data['nick_name']}} has just delegated their support to {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic: <b>{{ $data['object']}}</b>
 @endif

 </p>
@component('mail::button', ['url' => url('/') . '/' . $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent