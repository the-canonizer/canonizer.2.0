<div class="panel panel-default">
    <div class="panel-heading">

        <div class="level">
            <?php
                $topic = App\Model\Topic::getLiveTopic($threads->topic_id); 
                $namespace_id = 1;
                if(isset($topic->topic_num)){
                    $namespace_id = $topic->namespace_id;
                }
               $userUrl = route('user_supports',$reply->user_id)."?topicnum=".$threads->topic_id."&campnum=".$threads->camp_id."&namespace=".$namespace_id."#camp_".$threads->topic_id."_".$threads->camp_id;
            ?>
            <a href="{{$userUrl}}">
                {{ $reply->owner->nick_name }}
            </a> replied on {{ to_local_time($reply->updated_at)}}
            <br><br>
        </div>

    </div>

    <div class="panel-body" style="word-break:break-word">
        {!! html_entity_decode($reply->body) !!}
    </div>
</div>
