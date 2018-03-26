<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#">
            {{ $reply->owner->first_name }}
        </a> replied {{ $reply->created_at->diffForHumans() }}
    </div>
    <div class="panel-body">
        {{ $reply->body}}
    </div>
</div>