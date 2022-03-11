@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Edit News</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
    <div class="row col-sm-12 justify-content-between">
        <div class="col-sm-12 margin-btm-2">
            <form action="{{ url('/newsfeed/update')}}" onsubmit="return submitForm(this)" method="post" id="topicForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="topic_num" value="{{$topicnum}}"/>
                <input type="hidden" name="camp_num" value="{{$campnum}}" />
                <input type="hidden" name="topic_slug" value="{{$topic}}" />
                <div style="float:left;clear:both;width:100%" id="sortable" class="nubeT">
                    @foreach($news as $key=>$feed)
                    <div style="position: relative;" class="sortable rowNews " title="drag & drop to change order">
                        <input type="hidden" class="final_news_order" name="news_order[]" id="news_order_{{$key + 1}}" value="{{ $key + 1  }}">
                        <span class="stepNum inset news_order">{{$key+1}}</span>
                        <div class="form-group col-sm-6">
                            <label for="topic name">Display Text ( Limit 256 Chars ) <span style="color:red">*</span></label>
                            <textarea style="min-height: 61px;" onkeydown="restrictTextField(event,256)" type="text" name="display_text[]" class="form-control" id="display_text">{{ old('display_text.'.$key,$feed->display_text)}}</textarea>
                            @if ($errors->has('display_text.'.$key)) <p class="help-block">{{ $errors->first('display_text.'.$key) }}</p> @endif
                        </div>            
                        <div  class="form-group col-sm-6">
                            <label for="namespace">Link ( Limit 2000 Chars ) <span style="color:red">*</span></label>
                            <input type="text" maxlength="2000" onkeydown="restrictTextField(event,2000)" name="link[]" class="form-control" id="link" value="{{old('link.'.$key,$feed->link)}}">
                            @if ($errors->has('link.'.$key)) <p class="help-block">{{ $errors->first('link.'.$key) }}</p> @endif
                            <span class="childOpt-news"> <input type="checkbox" name="available_for_child[{{$key}}]" value="1" {{ (old('available_for_child.0',$feed->available_for_child)) ? "checked" : ""  }}>Available for child camps</span>
                        </div>
                    </div>
                    @endforeach
                    

                </div>
                <button type="submit" id="submit" class="btn btn-login">Submit</button>
                <?php 
                   $link = \App\Model\Camp::getTopicCampUrl($topicnum,$camp_num,time());
                ?>
                <a href="<?php echo $link; ?>" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>   
</div>  <!-- /.right-whitePnl-->

<script>
    $(function () {
        $('#sortable').sortable({
            containment: "parent",
            placeholder: "ui-state-highlight",
            cursor: 'move',
            opacity: 0.6,
            update: function (event, ui) {
                  $("#sortable").find('.sortable').each(function (i, v) {
                    $(v).find('.news_order').text(i + 1);
                    $(v).find('.final_news_order').val(i + 1);
                    var prefix = "available_for_child[" + i + "]";
                    $(v).find('input[type="checkbox"]').attr('name',prefix);
                });
            }
        });

    });
</script>
@endsection

