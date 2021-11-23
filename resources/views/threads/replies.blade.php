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
                //ne() function returns true if $date1 is not equal to $date2.
                $checkdate =App\Model\Topic::checkDateNotEql($reply->updated_at,$reply->created_at);
            ?>
            <a href="{{ $userUrl }}">
                {{ $reply->owner->nick_name }}
            </a>
            <?php if($checkdate){ echo "updated"; } else{ echo "replied"; } ?>
                 {{ Carbon\Carbon::createFromTimestamp( $reply->updated_at )->diffForHumans() }}
                ({{ to_local_time( $reply->updated_at )  }})
                @if(!empty($userNicknames)) 
                    @if($userNicknames[0]->id==$reply->user_id) 
                        <a href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $reply->c_thread_id }}-{{ $reply->id }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    @endif
                @endif
               <br><br>
        </div>

    </div>

    <div class="panel-body" style="word-break:break-word">
        {!! html_entity_decode($reply->body) !!}
    </div>
</div>
