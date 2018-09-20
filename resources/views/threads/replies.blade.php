<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#">
            {{ $reply->owner->nick_name }}
        </a> replied {{ $reply->created_at->diffForHumans() }}
    </div>
    <div class="panel-body">
        {!! nl2br(e(preg_replace('/[^a-zA-Z0-9_ \-,%&=?.:\/"|\r|\n]/s','', $reply->body))) !!}


    </div>
</div>
