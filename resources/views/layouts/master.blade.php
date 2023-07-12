<!DOCTYPE html>
<html lang="sv-SE">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name='robots' content='noindex, nofollow' />
        <title>Teckna försäkring | Dunstan</title>
        <link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	    <link rel="icon" href="{{ asset('img/favicon-32x32.png') }}" sizes="32x32" />
		<link rel="icon" href="{{ asset('img/favicon-192x192.png') }}" sizes="192x192" />
		<link rel="apple-touch-icon" href="{{ asset('img/favicon-180x180.png') }}" />
		<meta name="msapplication-TileImage" content="{{ asset('img/favicon-270x270.png') }}" />
        <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ mix('css/konfigurator.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" media="print" onload="this.media='all'">
        @yield('styles')
        @stack('styles')
{{--        <link href="{{ asset('css/auth.css') }}" rel="stylesheet">--}}
        <!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PFL6M43');</script>
		<!-- End Google Tag Manager -->
        <!-- Cookie information -->
        <script id="CookieConsent" src="https://policy.app.cookieinformation.com/uc.js"
        data-culture="SV" type="text/javascript"></script>
        <!-- End Cookie information -->
    </head>
    <body>
    	<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFL6M43"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
    	<div class="error-message-wrapper">
    		<div class="error-message-text">
	            <span class="validation-error-message">
	                Hoppla! Du måste göra klart alla val innan du kan gå vidare till nästa steg.
	            </span>
	            <span class="custom-error-message"></span>
            </div>
    	</div>
        @include('includes.sidebar')
        <main id="app">
        	@include('includes.header')
            @yield('content')
        </main><!--// #app end -->
        @include('includes.footer')
        <script src="//code.tidio.co/fauzabvqb0arvonsn5y1aflzaxns9vol.js" async></script>
    </body>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/konfigurator.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            window.konfigurator = new Konfigurator({
                form: $('#main-form'),
                progressbar: $('#progress'),
                footer: $('#app-footer'),
                urls: {
                    step: '{{ route('step.path', ['step' => '%step%']) }}',
                    validate: '{{ route('step.validate', ['step' => '%step%']) }}',
                }
            });

            $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
        });
    </script>
</html>
