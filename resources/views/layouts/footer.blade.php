<footer class="sticky-footer">
    <div class="container">
        <div class="row">
            <!--<div class="col-sm-4 pd-l-0"><span>Sponsers: &nbsp;&nbsp; <img src="img/mta-thumb.png"/></span></div>-->
            <div class="col-sm-12 text-center">
                <small>Copyright owned by the volunteers contributing to the system and its contents (2006 - {{ date('Y')}})</small>
                <small>Comments and Questions: <a href="mailto:support@canonizer.com">support@canonizer.com</a></small>
				<small><a href="{{url('privacypolicy')}}">Privacy Policy</a> | Patent: US 8,160,970 B2 | <a href="{{url('termservice')}}">Terms & Services</a></small>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JavaScript-->

<script src="{{ URL::asset('/js/popper/popper.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<!-- Core plugin JavaScript-->
<script src="{{ URL::asset('/js/jquery-easing/jquery.easing.min.js') }}"></script>
<!-- Custom scripts for all pages-->
<script id="custom_script" src="{{ URL::asset('/js/canonizer.min.js') }}"></script>