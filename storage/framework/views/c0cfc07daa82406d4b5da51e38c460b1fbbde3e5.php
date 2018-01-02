<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Canonizer</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <link rel="shortcut icon" href="img/favicon.ico" >
        <!-- Bootstrap core CSS-->
        <link href="<?php echo e(URL::asset('/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="<?php echo e(URL::asset('/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="<?php echo e(URL::asset('/css/canonizer.css')); ?>" rel="stylesheet">
        
        <!-- jquery  -->
        <script src="<?php echo e(URL::asset('/js/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('/js/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('/js/jquery-ui/jquery-ui.js')); ?>"></script>
        <link href="<?php echo e(URL::asset('/js/jquery-ui/jquery-ui.css')); ?>" rel="stylesheet" type="text/css">


    </head>
    <body>

        <?php $__env->startSection('sidebar'); ?>
        <nav class="navbar navbar-expand-lg" id="mainNav">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(url('/img/logo.png')); ?>">
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav search-box">
                    <li class="nav-item">
                        <form class="form-inline">
                            <div class="input-group search-panel">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-slct-dpdwn" data-toggle="dropdown">
                                        <span id="search_concept">Canonizer.com</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#web">Web</a></li>
                                        <li><a href="#canonizer.com">Canonizer.com</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control search" name="x" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-search" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item dropdown_li">
                        <?php if(Auth::check()): ?>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-user"></i> <?php echo e(Auth::user()->first_name . ' ' . Auth::user()->last_name); ?> </a>
                            <span class="caret"></span>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo e(route('settings')); ?>">Account Settings</a></li>
                                <li><a href="<?php echo e(url('/logout')); ?>">Logout</a></li>
                            </ul>                        

                        </div>
                        <?php else: ?>
                        <a class="nav-link" href="<?php echo e(url('/login')); ?>"><i class="fa fa-fw fa-user"></i> Log in</a>
                        <a class="nav-link" href="<?php echo e(url('/register')); ?>"><i class="fa fa-fw fa-user-plus"></i> Register </a>
                        <?php endif; ?>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <ul class="uppermenu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(url('/')); ?>">
                                <span class="nav-link-text">Canonizer Main</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-text">What is Canonizer.com</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-text">Browse</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(url('/topic/create')); ?>">
                                <span class="nav-link-text">Create New Topic</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-text">Upload File</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="nav-link-text">Help</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="lowermneu canoalgo">
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#canoalgo">
                                <span class="nav-link-text">Canonizer</span>
                            </a>
                            <ul class="sidenav-second-level collapse show" id="canoalgo">
                                <li>
                                    <span>Algorithm Information:</span>
                                    <select>
                                        <option>One Person One Vote</option>
                                        <option>Mind Experts</option>
                                        <option>Computer Science Experts</option>
                                        <option>Ph.D.</option>
                                        <option>Christian</option>
                                        <option>Secular / Non Religious</option>
                                        <option>Mormon</option>
                                        <option>Universal Unitarian</option>
                                        <option>Atheist</option>
                                        <option>Transhumanist</option>
                                    </select>
                                </li>
                                <li>
                                    <span>Canonizer Algorithm:</span>
                                    <div class="filter">Filter < <input type="number" value="0.001"/></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="lowermneu asof">
                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#asof">
                                <span class="nav-link-text">As Of</span>
                            </a>
                            <ul class="sidenav-second-level collapse show" id="asof">
                                <li>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio1" id="radio1" value="option1">
                                        <label for="radio1">include review</label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio2" id="radio2" value="option2">
                                        <label for="radio2">default</label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio3" id="radio3" value="option3">
                                        <label for="radio3">as of (yy/mm/dd)</label>
                                    </div>
                                    <div><input type="text"/></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </ul>
            </div>
        </nav>
        <?php echo $__env->yieldSection(); ?>

        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        <div class="homeADDright">
			<?php echo $__env->make('partials.advertisement', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>    
            <!-- footer -->
            

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
        </div>
    </div>
</body>
</html>

<?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>