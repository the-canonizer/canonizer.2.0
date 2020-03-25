@component('mail::message')
<p>Hi  {{ $user->first_name }} {{ $user->last_name }}, </p>

<p>
    {{ $data['nick_name']->nick_name }} has made the following post to the Camp
    <a href="{{ url('/').'/'.$data['camp_url'] }}"> {{ $data['camp_name'] }} </a> forum:

</p>

<h4>Thread Title: </h4>
<p> <a href="{{ url('/').'/'.$link }}">{{ $data['thread'][0]->title }}</a> </p>

<h4>Post: </h4>

<p> {!! $data['post'] !!}. </p>

<h4>Note:</h4>
@if(isset($data['subscriber']) && $data['subscriber'] == 1)
<p>
    If you do not wish to receive these notifications, you can unsubscribe from the camp.
     We request that all subscriber of a camp continue to receive and take some responsibility for the camp.
</p>
@else
 <p>
    If you do not wish to receive these notifications, you can either
    delegate your support to some other camp supporter in the topic, or
    remove your support from the camp.  We request that all direct supporters of a
    camp continue to receive and take some responsibility for the camp.
</p>
@endif

<p>  Sincerely, </p>
<p> {{ config('app.email_signature') }}</p>
@endcomponent
