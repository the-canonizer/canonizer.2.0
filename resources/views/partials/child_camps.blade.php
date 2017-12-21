 
    @foreach($childs as $child)    
    <li>        
        <span class="{{ (count($child->childrens($child->topic_num,$child->camp_num)) > 0) ? 'parent' : '' }}"><i class="fa fa-arrow-down"></i> {{ $child->title}} <div class="badge">48.25</div></span>
        <ul>
            <li class="create-new-li"><span><a href="{{ route('camp.create',['topicnum'=>$child->topic_num,'campnum'=>$child->camp_num])}}">< Create A New Camp ></a></span></li>
            @if(count($child->childrens($child->topic_num,$child->camp_num)) > 0)
           
            @endif
        </ul>
       
    </li>
    @endforeach
