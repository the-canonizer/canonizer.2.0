@component('mail::message')
 Hello {{ $user->first_name }} {{ $user->last_name }},<br/>
 <?php if(isset($data['mail_to_parent']) &&  $data['mail_to_parent']){ ?>
     
 <p>
 <a target="_blank" href="<?= route('user_supports',$data['nick_name_id']) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>">{{ $data['nick_name']}}</a> has just removed their delegated support from you (<a target="_blank" href="<?= route('user_supports',$data['delegated_user_id']) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"> {{(isset($data['delegated_user'])) ? $data['delegated_user'] :'you'}} </a>) in this topic: <a href="{{ $link }}"><b>{{ $data['topic']->topic_name}}</b></a>
</p>
<?php } else{?>
<p>
   You (<a target="_blank" href="<?= route('user_supports',$data['nick_name_id']) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>">{{ $data['nick_name']}}</a>) have just removed your delegated support from <a target="_blank" href="<?= route('user_supports',$data['delegated_user_id']) .'?topicnum=&campnum=&namespace=' . $data['topic']->namespace_id; ?>"> {{(isset($data['delegated_user'])) ? $data['delegated_user'] :'you'}} </a> in this topic: <a href="{{ $link }}"><b>{{ $data['topic']->topic_name}}</b></a>
</p>
<?php } ?>
Sincerely,<br>
{{ config('app.email_signature') }}
@endcomponent 