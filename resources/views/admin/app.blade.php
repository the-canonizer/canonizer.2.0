<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Canonizer Admin</title>

        <!-- <link rel="shortcut icon" href="img/favicon.ico" > -->
        <!-- Bootstrap core CSS-->
        <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
         <link href="{{ URL::asset('/css/admin.css') }}" rel="stylesheet">
           <link href="{{ URL::asset('/select2/dist/css/select2.min.css') }}" rel="stylesheet">

        <link href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
         <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
         <script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>
          <script src="{{ URL::asset('/select2/dist/js/select2.min.js') }}"></script>
       
    </head>
<body>
<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-9">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="#">Canonizer Admin</a></h1>
	              </div>
	           </div>
               <div class="col-md-3 adminuser">
                    @if(Auth::check())
                        <span class="brsr-name">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name}}</span>

                    @endif
               </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-3">
		  	<div class="sidebar content-box" style="display: block;">
                <?php $route = Route::getCurrentRoute()->getActionMethod();
                 ?>
                <ul class="nav">
                    <!-- Main menu -->
					          <li class ="{{ ($route=='getIndex') ? 'current':''}}"><a href="{{ url('/admin') }}"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <li class="{{ ($route=='namespace') ? 'current':''}}"><a href="{{ url('/admin/namespace') }}"><i class="glyphicon glyphicon-home"></i> Namespace</a></li>
                    <li class="{{ ($route=='getNamespaceRequests') ? 'current':''}}"><a href="{{ url('/admin/namespace-requests') }}"><i class="glyphicon glyphicon-calendar"></i> Namespace Requests</a></li>
                    <li class="{{ ($route=='getIndex' & str_contains(Request::fullUrl(), 'users')) ? 'current':''}}"><a href="{{ url('/admin/users') }}"><i class="glyphicon glyphicon-calendar"></i> Users</a></li>
                    <li class="{{ ($route=='index' & str_contains(Request::fullUrl(), 'templates')) ? 'current':''}}"><a href="{{ url('/admin/templates') }}"><i class="glyphicon glyphicon-pencil"></i> Templates</a></li>
                    <li class="{{ ($route=='getSendmail') ? 'current':''}}"><a href="{{ url('/admin/sendmail') }}"><i class="glyphicon glyphicon-pencil"></i> Send Email</a></li>
                    <li class="{{ ($route=='index' & str_contains(Request::fullUrl(), 'videopodcast')) ? 'current':''}}"><a href="{{ url('/admin/videopodcast') }}"><i class="glyphicon glyphicon-facetime-video"></i> Video Podcast</a></li>
                    <!--<li><a href="stats.html"><i class="glyphicon glyphicon-stats"></i> Statistics (Charts)</a></li>
                    <li><a href="tables.html"><i class="glyphicon glyphicon-list"></i> Tables</a></li>
                    <li><a href="buttons.html"><i class="glyphicon glyphicon-record"></i> Buttons</a></li>
                    
                    <li><a href="forms.html"><i class="glyphicon glyphicon-tasks"></i> Forms</a></li>-->
                    <li class="{{ ($route=='logout') ? 'current':''}}"><a href="{{ url('logout?from=admin') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
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
<script>
    
    function restrictTextField(e,limitlength){
    var charLength = $(e.target).val().length;
     if (charLength >= limitlength  && e.keyCode !== 46 && e.keyCode !== 8 ) {
           e.preventDefault();
           $(e.target).val($(e.target).val().substring(0,limitlength));
    }
}
</script>
</body>
</html>
