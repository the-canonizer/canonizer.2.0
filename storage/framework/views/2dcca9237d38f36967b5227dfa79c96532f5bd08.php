<?php $__env->startSection('content'); ?>
<div class="page-titlePnl">
    <h1 class="page-title">Canonizer Main Page</h1>
    <small>( This is a free open source prototype being developed by volunteers. <br>
        Please be patient with what we have so far and/or be willing to help. )</small> 
</div>       	
<div class="right-whitePnl">
    <div class="container-fluid">
        <div class="Gcolor-Pnl">
            <h3>Canonizer Information</h3>
            <div class="content">
                <p>Canonizer.com is a consensus building system enabling people to build consensus where none has been possible before. It is a wiki system that solves the critical problems suffered by Wikipedia. It solves edit wars by providing contributors the ability to create and join camps and it provides a measure of information reliability by providing relative measures of expert consensus. Unlike other information sources, such as peer reviewed documents, where there is far too much information for any individual to fully comprehend (We just blew past 20K documents in the field of consciousness) this open survey system provides real time concise and quantitative descriptions of the current and emerging leading theories. Theories that have been falsified by new scientific evidence are being instantly measured to the degree experts are abandoning those theories for newer better ones. The non repetitive, continually ratcheting up process significantly accelerates and amplifies the education and wisdom of the entire crowd.</p>

                <p>Many people jump to the false conclusion that the Goal of Canonizer.com is to measure 'truth' via popular consensus. In fact, the goal is just the opposite. Crowds tend to behave in ignorant herding behavior, not unlike sheep. Various 'camps' and religions have a strong desire and interest in anything that promotes what they believe. They are highly motivated to dismiss or ignore anything that goes against their beliefs. The goal of Canonizer.com is to enable the crowd more rapidly recognize when this is happening, making it easier for them to measure for the quality of a good new theory they may want to pay attention to, even if it is counter to their currently preferred beliefs. The bottom line being our goal is not to measure truth via popularity, but to enable emerging minority theories to be more rapidly herd above any such biased bleating of any herd.</p>
            </div>
        </div>
        <div class="Lcolor-Pnl">
            <h3>Canonized list for  
                <select>
                    <option>General</option>
                    <option>Corporations</option>
                </select>
            </h3>
            <div class="content">
                <div class="tree">
                    <ul class="mainouter">
                        <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $camps = [];
                        $camps = $topic->childrens($topic->topic_num, $topic->camp_num);
                        ?>
                        <li>
                            <span class="<?php echo e((count($camps) > 0) ? 'parent' : ''); ?>"><i class="<?php echo e((count($camps) > 0) ? 'fa fa-arrow-down' : ''); ?>"></i> <?php echo e($topic->title); ?> <div class="badge">48.25</div></span>
                            <?php if(count($camps) > 0): ?>
                            <ul>
                                <?php $__currentLoopData = $camps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                               
                                   <?php echo $__env->make('partials.child_camps',$camp, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>


            </div>
        </div>
    </div>
    <!-- /.container-fluid-->
</div>  <!-- /.right-whitePnl-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>