@component('mail::message')
Dear User,<br/>
 
<p>You delegated your support to <a target="_blank" href="<?= route('user_supports',$data['promotedFrom']->id) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"><?= $data['promotedFrom']->nick_name; ?></a> who delegated their support to <a target="_blank" href="<?= route('user_supports',$data['promotedTo']->id) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"><?= $data['promotedTo']->nick_name; ?></a> in <a href="<?= $data['camp_link']; ?>" target="_blank"><?= $data['camp']->camp_name; ?></a> camp in <a href="<?= $data['topic_link']; ?>" target="_blank"><?= $data['topic']->topic_name; ?></a> topic.</p>
<p>They have stopped delegating their support in this topic so your support has been delegated to <a target="_blank" href="<?= route('user_supports',$data['promotedTo']->id) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"><?= $data['promotedTo']->nick_name; ?></a>.</p>
<p><b>No other action is required.</b></p>


Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
