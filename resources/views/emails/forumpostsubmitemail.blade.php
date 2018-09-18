@component('mail::message')
<p>Hi Canonizer User,</p>

<p>
    {{ $data['nick_name']->nick_name }} has made the following post to the Camp
    <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a> forum:

</p>

<h4>Thread Title: </h4>
<p> <a href="{{ url('/').'/'.$link }}">{{ $data['thread'][0]->title }}</a> </p>

<h4>Post: </h4>
<p>{{$data['post']}}</p>

<h4>Note:</h4>
<p>
    If you do not wish to receive these notifications, you can either
    delegate your support to some other camp supporter in the topic, or
    remove your support from the camp.  We request that all direct supporters of a
    camp continue to receive and take some responsibility for the camp.
</p>

<p>  Sincerely, </p>
<p> The Canonizer Team </p>
@endcomponent
