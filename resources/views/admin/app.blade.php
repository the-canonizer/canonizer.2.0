<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Canonizer Admin</title>

        <link rel="shortcut icon" href="img/favicon.ico" >
        <!-- Bootstrap core CSS-->
        <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
         <link href="{{ URL::asset('/css/admin.css') }}" rel="stylesheet">

        <link href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
         <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
       
    </head>
<body>
<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-5">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="#">Canonizer Admin</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-3">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
					<li class="current"><a href="{{ url('/admin') }}"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <li class="current"><a href="{{ url('/admin/namespace') }}"><i class="glyphicon glyphicon-home"></i> Namespace</a></li>
                    <li><a href="{{ url('/admin/namespace-requests') }}"><i class="glyphicon glyphicon-calendar"></i> Namespace Requests</a></li>
                    <li><a href="{{ url('/admin/users') }}"><i class="glyphicon glyphicon-calendar"></i> Users</a></li>
                    <!--<li><a href="stats.html"><i class="glyphicon glyphicon-stats"></i> Statistics (Charts)</a></li>
                    <li><a href="tables.html"><i class="glyphicon glyphicon-list"></i> Tables</a></li>
                    <li><a href="buttons.html"><i class="glyphicon glyphicon-record"></i> Buttons</a></li>
                    <li><a href="editors.html"><i class="glyphicon glyphicon-pencil"></i> Editors</a></li>
                    <li><a href="forms.html"><i class="glyphicon glyphicon-tasks"></i> Forms</a></li>-->
                    <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
             </div>
		  </div>
		  <div class="col-md-9">
		  	@yield('content')
		  </div>
		</div>
    </div>

    <footer>
         <div class="container">
         
            <div class="copy text-center">
               Canonizer 2.0 Copyright &copy;<?=date('Y')?>
            </div>
            
         </div>
      </footer>
</body>
</html>
