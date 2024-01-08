{{-- THIS SHOULD EXTEND A LAYOUT FOR SCAFFHOLDING THE CSS AND JS AND OTHER SHIT--}}
<title>{{ $title }} - DashAll</title>
<base href="/">

<meta name="description" content="DashAll Description" />

<meta property="og:image" content="http://dashall.ca/images/fb_thumbnail.png" />
<meta property="og:site_name" content="DashAll" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="image_src" href="./images/fb-thumbnail.png" />
<link rel="stylesheet" type="text/css" href="./css/app.css" />
<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css" />

<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHSviZvR2cQZUrTtxImGXM0c7y1VVoOSg&libraries=places"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73093301-1', 'auto');
  ga('send', 'pageview');
</script>


<meta name="theme-color" content="#d53030">
<link rel="icon" sizes="192x192" href="./images/chrome-icon.png">
<meta name="mobile-web-app-capable" content="yes">

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');


fbq('init', '1172519806112789');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1172519806112789&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<div id="fb-root"></div>
<script async>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=169744616753519";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
