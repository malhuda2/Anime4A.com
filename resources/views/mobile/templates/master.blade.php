<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 1:57 AM
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('Title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="@lang('messages.pagename2')" />
    <meta name="Robots" content="index, follow" />
    <meta name="description" content="@lang('messages.description')">
    <meta name="keywords" content="Anime Vietsub, Anime Online, Anime Download, Anime HD" />
    <meta name="abstract" content="Copyright © 2012 Anime TV Watch Free Vietnamese Subbed Dubbed Anime Online">
    <meta name="Search Engines" content="www.altaVista.com, www.aol.com, www.infoseek.com, www.excite.com, www.hotbot.com, www.lycos.com, www.magellan.com, www.looksmart.com, www.cnet.com, www.voila.com, www.google.fr, www.google.com, www.google.com.vn, www.yahoo.fr, www.yahoo.com, www.alltheweb.com, www.msn.com, www.netscape.com, www.nomade.com">
    <meta property="fb:app_id" content="289914814743628" />
    <meta property="og:type" content="video.movie" />
    <meta property="og:title" content="@lang('messages.title')" />
    <meta property="og:description" content="@lang('messages.description')" />
    <meta property="og:url" content="{{ Request::root() }}" />
    <meta property="og:image" content="" />
    <base href="{{ Request::root() }}/" /><!--[if IE]></base><![endif]-->
    <link href="/favicons/anime4a.com.png" rel="shortcut icon" type="image/x-icon" />
    <link rel="author" href="{{ Request::root() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://apis.google.com/js/api:client.js"></script>
    <script>
        var googleUser = {};
        var startApp = function() {
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                auth2 = gapi.auth2.init({
                    client_id: '28215860207-380s5r214hqt9lgpa3eatpl14idpndjs.apps.googleusercontent.com',
                    cookiepolicy: 'single_host_origin',
                    // Request scopes in addition to 'profile' and 'email'
                    scope: 'profile email'
                });
                attachSignin(document.getElementById('GGLoginBtn'));
            });
        };

        function attachSignin(element) {
            auth2.attachClickHandler(element, {},
                    function(googleUser) {
                        // login success
                        gg_login(googleUser);
                    }, function(error) {
                        alert(JSON.stringify(error, undefined, 2));
                    });
        }
    </script>

    @yield('stylesheet')
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88445154-1', 'auto');
  ga('send', 'pageview');

</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7114750780411306",
    enable_page_level_ads: true
  });
</script>
<div class="shadow" style="position: fixed; display: none"></div>

@yield('MainUrl')

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '289914814743628',
            xfbml      : true,
            version    : 'v2.8'
        });
    };
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div id="wrap">

    @include('mobile.pages.header')

    <div id="container">
        <div id="pagebody">

            @yield('content')

        </div>
    </div>

    @yield('controlBar')

    @include('mobile.pages.footer')

</div>
</body>
</html>
