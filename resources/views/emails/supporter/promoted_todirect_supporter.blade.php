@component('mail::message')
Dear User,<br/>
 
<p>You delegated your support to <a target="_blank" href="<?= route('user_supports',$data['promotedFrom']->id) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"><?= $data['promotedFrom']->nick_name; ?></a> who was directly supporting <a href="<?= $data['camp_link']; ?>" target="_blank"><?= $data['camp']->camp_name; ?></a> camp in <a href="<?= $data['topic_link']; ?>" target="_blank"><?= $data['topic']->topic_name; ?></a> topic.</p>
<p> They have entirely removed their support of all camps in this topic, so you have been promoted to a direct supporter in their place. Direct supporters are expected to participate in the maintenance of camps, including receiving and where necessary, responding to emails regarding the maintenance of directly supported camps. If you are not able to do this, you can delegate your support to any other supporter in the <a href="<?= $data['camp_link']; ?>" target="_blank"><?= $data['camp']->camp_name; ?></a> camp. Or you can entirely remove your support of all camps <a href="<?php echo url('support/'.$data['url_portion'] );?>" target="_blank">here</a>.</a></p>

Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent
