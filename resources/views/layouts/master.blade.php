<!DOCTYPE html>
<html lang="sv-SE">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PFL6M43');</script>
        <!-- End Google Tag Manager -->
        <!-- TrustBox script -->
        <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
        <!-- End TrustBox script -->
        <!-- Start VWO Async SmartCode -->
        <link rel="preconnect" href="https://dev.visualwebsiteoptimizer.com" />
        <!-- Start VWO Async SmartCode -->
        <link rel="preconnect" href="https://dev.visualwebsiteoptimizer.com" />
        <script type='text/javascript' id='vwoCode'>
            window._vwo_code || (function() {
            var account_id=788233,
            version=2.0,
            settings_tolerance=2000,
            hide_element='body',
            hide_element_style = 'opacity:0 !important;filter:alpha(opacity=0) !important;background:none !important',
            /* DO NOT EDIT BELOW THIS LINE */
            f=false,w=window,d=document,v=d.querySelector('#vwoCode'),cK='_vwo_'+account_id+'_settings',cc={};try{var c=JSON.parse(localStorage.getItem('_vwo_'+account_id+'_config'));cc=c&&typeof c==='object'?c:{}}catch(e){}var stT=cc.stT==='session'?w.sessionStorage:w.localStorage;code={use_existing_jquery:function(){return typeof use_existing_jquery!=='undefined'?use_existing_jquery:undefined},library_tolerance:function(){return typeof library_tolerance!=='undefined'?library_tolerance:undefined},settings_tolerance:function(){return cc.sT||settings_tolerance},hide_element_style:function(){return'{'+(cc.hES||hide_element_style)+'}'},hide_element:function(){return typeof cc.hE==='string'?cc.hE:hide_element},getVersion:function(){return version},finish:function(){if(!f){f=true;var e=d.getElementById('_vis_opt_path_hides');if(e)e.parentNode.removeChild(e)}},finished:function(){return f},load:function(e){var t=this.getSettings(),n=d.createElement('script'),i=this;if(t){n.textContent=t;d.getElementsByTagName('head')[0].appendChild(n);if(!w.VWO||VWO.caE){stT.removeItem(cK);i.load(e)}}else{n.fetchPriority='high';n.src=e;n.type='text/javascript';n.onerror=function(){_vwo_code.finish()};d.getElementsByTagName('head')[0].appendChild(n)}},getSettings:function(){try{var e=stT.getItem(cK);if(!e){return}e=JSON.parse(e);if(Date.now()>e.e){stT.removeItem(cK);return}return e.s}catch(e){return}},init:function(){if(d.URL.indexOf('__vwo_disable__')>-1)return;var e=this.settings_tolerance();w._vwo_settings_timer=setTimeout(function(){_vwo_code.finish();stT.removeItem(cK)},e);var t=d.currentScript,n=d.createElement('style'),i=this.hide_element(),r=t&&!t.async&&i?i+this.hide_element_style():'',c=d.getElementsByTagName('head')[0];n.setAttribute('id','_vis_opt_path_hides');v&&n.setAttribute('nonce',v.nonce);n.setAttribute('type','text/css');if(n.styleSheet)n.styleSheet.cssText=r;else n.appendChild(d.createTextNode(r));c.appendChild(n);this.load('https://dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&vn='+version)}};w._vwo_code=code;code.init();})();
        </script>
        <!-- End VWO Async SmartCode -->
        <!-- Cookie information -->
        <script id="CookieConsent" src="https://policy.app.cookieinformation.com/uc.js" data-culture="SV" type="text/javascript"></script>
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
