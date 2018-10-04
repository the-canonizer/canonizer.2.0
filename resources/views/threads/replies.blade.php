<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#">
            {{ $reply->owner->nick_name }}
        </a> replied {{ $reply->created_at->diffForHumans() }}
    </div>
    <div class="panel-body" style="word-break:break-all">
        {!! html_entity_decode($reply->body) !!}
    </div>
</div>
