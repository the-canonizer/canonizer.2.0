<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Canonizer</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <link rel="shortcut icon" href="img/favicon.ico" >
        <!-- Bootstrap core CSS-->
        <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="{{ URL::asset('/css/canonizer.css') }}" rel="stylesheet">
        
        <!-- jquery  -->
        <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/js/jquery-ui/jquery-ui.js') }}"></script>
        <link href="{{ URL::asset('/js/jquery-ui/jquery-ui.css') }}" rel="stylesheet" type="text/css">


    </head>
    <body>

        @section('sidebar')
        <nav class="navbar navbar-expand-lg" id="mainNav">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ url('/img/logo.png')}}">
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav search-box">
                    <li class="nav-item col-sm-7">
                       <form method="get" action="http://www.google.com/custom" target="_top">
                            <div class="input-group search-panel">
                               <table>
									<tr>
										<td class="radio radio-primary">
										<input type="radio" name="sitesearch" value="" checked id="ss0"></input>
										<label for="ss0" title="Search the Web"><font size="-1" color="black">Web</font></label></td>
										<td class="radio radio-primary">
										<input type="radio" name="sitesearch" value="canonizer.com" id="ss1" checked></input>
										<label for="ss1" title="Search canonizer.com"><font size="-1" color="black">Canonizer.com</font></label></td>
									</tr>
								</table>
                                <input type="hidden" name="search_param" value="all" id="search_param">         
                                <input type="text" class="form-control search" name="q" id="sbi" placeholder="Search for...">
                               
									<input type="submit" name="sa" value="Google Search" id="sbb"></input>
									<input type="hidden" name="client" value="pub-6646446076038181"></input>
									<input type="hidden" name="forid" value="1"></input>
									<input type="hidden" name="ie" value="ISO-8859-1"></input>
									<input type="hidden" name="oe" value="ISO-8859-1"></input>
									<input type="hidden" name="cof" value="GALT:#0066CC;GL:1;DIV:#999999;VLC:336633;AH:center;BGC:FFFFFF;LBGC:FF9900;ALC:0066CC;LC:0066CC;T:000000;GFNT:666666;GIMP:666666;LH:43;LW:220;L:http://canonizer.com/images/CANONIZER.PNG;S:http://;FORID:1"></input>
									<input type="hidden" name="hl" value="en"></input>
                              </div>
                        </form>
                    </li>
                    <li class="nav-item col-sm-5 text-right" style="padding-right:0px;">
                        @if(Auth::check())
						<div class="dropdown">
                            Browsing as: <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-user"></i> {{ Auth::user()->first_name . ' ' . Auth::user()->last_name}} </a>
                            <span class="caret"></span>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('settings')}}">Account Settings</a></li>
                                <li><a href="{{ url('/logout')}}">Logout</a></li>
                            </ul>                        

                        </div>
                        @else
						<a class="nav-link guestLogin">Browsing as: Guest_31</a>	
                        <a class="nav-link" href="{{ url('/login')}}"><i class="fa fa-fw fa-user"></i> Log in</a>
                        <a class="nav-link" href="{{ url('/register')}}"><i class="fa fa-fw fa-user-plus"></i> Register </a>
                        @endif
                    </li>
                </ul>
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <ul class="uppermenu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/')}}">
                                <span class="nav-link-text">Canonizer Main</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('topic/10-Canonizer-organization-home-page-/1')}}">
                                <span class="nav-link-text">What is Canonizer.com</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/browse')}}">
                                <span class="nav-link-text">Browse</span>
                            </a>
                        </li>
                        
                        @if(strpos(url()->current(), 'forum') == true )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/create">
                                <span class="nav-link-text">Create New Thread</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/topic/create')}}">
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
                                    
									<span>Canonizer Algorithm:</span>
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
									<a href="<?php echo url('topic/53-Canonized-Canonizer-Algorithms/2') ?>"><span>Algorithm Information</span></a>
                                </li>
								
                                <li>
                                    
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
								 <form name="as_of" id="as_of" method="GET">
								   <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="radio radio-primary">
										<input type="radio" <?php echo (isset($_REQUEST['asof']) && $_REQUEST['asof']=="review") ? "checked='checked'" : '';?> class="asofdate" name="asof" id="radio1" value="review">
										<label for="radio1">include review</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" <?php echo ((isset($_REQUEST['asof']) && $_REQUEST['asof']!="review") || !isset($_REQUEST['asof'])) ? "checked='checked'" : '';?> class="asofdate" name="asof" id="radio2" value="default">
										<label for="radio2">default</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" <?php echo (isset($_REQUEST['asof']) && $_REQUEST['asof']=="bydate") ? "checked='checked'" : '';?> class="asofdate" name="asof"id="radio3" value="bydate">
										<label for="radio3">as of (yy/mm/dd)</label>
									</div>
									<div><input type="text" id="asofdate" name="asofdate" value="<?php echo isset($_REQUEST['asofdate']) ? $_REQUEST['asofdate']: '';?>"/></div>
								</form>	
                                </li>
                            </ul>
                        </li>
                    </ul>
                </ul>
            </div>
        </nav>
        @show

        <div class="content-wrapper">
            @yield('content')
        <div class="homeADDright">
			@include('partials.advertisement')
		</div>    
            <!-- footer -->
            @extends('layouts.footer')
            
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#asofdate").datepicker({
                changeMonth: true,
                changeYear: true,
				dateFormat: 'yy/mm/dd'
            });			
			
			$(".asofdate, #asofdate").change(function(){
				// Do something interesting here
				 var value = $('#asofdate').val();
				 
				 var bydate = $("input[name='asof']:checked"). val();
				
				 if(value=="" && bydate == 'bydate') {
					 $('#asofdate').focus();
				  return false;
				 }	 
				 $('#as_of').submit();
			});
			
        })
    </script>	
</body>
</html>
