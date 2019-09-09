@include('web.partials.head')
@include('web.partials.topnav')
@include('web.partials.sidebar')

@yield('contentWeb')
	<!-- Home -->

	@include('web.partials.footer')
</div>

<script src="{{ asset('assetsWeb/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assetsWeb/styles/bootstrap4/popper.js')}}"></script>
<script src="{{ asset('assetsWeb/styles/bootstrap4/bootstrap.min.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/OwlCarousel2-2.2.1/owl.carousel.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/easing/easing.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/parallax-js-master/parallax.min.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/colorbox/jquery.colorbox-min.js')}}"></script>
<script src="{{ asset('assetsWeb/js/custom.js')}}"></script>
@yield('scriptWeb')
</body>
</html>