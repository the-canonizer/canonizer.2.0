<?php
$childcamps = [];
$childcamps = $camp->childrens($camp->topic_num, $camp->camp_num);
?>

<li>  
    <span class="{{ (count($childcamps) > 0) ? 'parent' : '' }}"><i class="{{ (count($childcamps) > 0) ? 'fa fa-arrow-down' : 'fa fa-arrow-right'}}"></i> {{ $camp->title}} <div class="badge">44</div></span>
    @if(count($childcamps) > 0)                                    
    <ul>
        @foreach($childcamps as $camp)

        
        @endforeach

    </ul>
    @endif
</li>