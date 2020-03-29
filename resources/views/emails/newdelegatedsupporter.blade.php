@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>
 @if (isset($data['support_added']) && $data['support_added'] == 1 )
 	{{ $data['nick_name']}} has just added their support to this camp: <a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @elseif(isset($data['support_deleted']) && $data['support_deleted'] == 1)
 {{ $data['nick_name']}} has just removed their support to this camp: <a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @else
 	{{ $data['nick_name']}} has just delegated their support to {{(isset($data['delegated_user']))? $data['delegated_user'] :'you'}} in this topic:<a href="{{ url('/') . '/' . $link }}"><b>{{ $data['object']}}</b></a>
 @endif

 </p>
@component('mail::button', ['url' => url('/') . '/' . $link])
Click here to See this topic.
@endcomponent

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent