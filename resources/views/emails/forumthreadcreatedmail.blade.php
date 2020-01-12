@component('mail::message')
Hi Canonizer User,

<p>
    {{ $data['nick_name']->nick_name }} has created the new thread
    <a href="{{ url('/').'/'.$link }}">{{ $data['thread_title'] }}</a>

</p>

<p>Camp Name: <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a></p>

<p>
    If you do not wish to receive these notifications, you can either
    delegate your support to some other camp supporter in the topic, or
    remove your support from the camp.  We request that all direct supporters of a
    camp continue to receive and take some responsibility for the camp.
</p>

<p>  Sincerely, </p>
<p> The Canonizer Team </p>
@endcomponent
