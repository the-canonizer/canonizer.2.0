@component('mail::message')
 Hi {{ $user->first_name }} {{ $user->last_name }},<br/>
 <p>Your proposed change submitted for <a href="{{ url('/').'/'.$link}}">{{ $data['object'] }} </a> has been objected to by {{ $data['nick_name'] }}</p>
 
@component('mail::button', ['url' => url('/') . '/' . $link])
Click here to See this topic for options you can take if there are objections
@endcomponent

<p>Include the following in that new help topic:</p>
<p>Given this, your options include: taking this to the <a href="{{ url('/').'/'.$data['forum_link']}}">camp forum</a> and negotiate for a unanimously agreed on change;  or you can push the disagreeable parts to a lower level sub camp; or you can fork the camp, completely then making your change to the forked camp (taking all who agree with you, splitting off to the new camp).  This is usually the method used when there is only one, or a few hold outs refusing to accept the change.  When everyone else moves to the new camp, they will be left alone, supporting the remaining unchanged camp, which will often be filtered out by most viewers.
</p>
Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent