<?php 
session_start();
ob_start();
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="format-detection" content="telephone=no">
  <meta charset="UTF-8">

  <title>logisticsfrog.com - My invoices</title>
      
  <!-- CSS -->
  <link href="<?= $_SESSION['href_location'] ?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/animate.min.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/font-awesome.min.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/form.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/calendar.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/style.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/icons.css" rel="stylesheet">
  <link href="<?= $_SESSION['href_location'] ?>css/generics.css" rel="stylesheet"> 
  <link href="<?= $_SESSION['href_location'] ?>css/custom-frog.css" rel="stylesheet"> 
</head>
<body id="skin-tectile">
<?php 
session_start();
ob_start();
include($_SESSION['ProjectPath']."/includes/sa-header.php") ?>
<div class="clearfix"></div>

<section id="main" class="p-relative" role="main">
    <!-- Content -->
    <section id="content" class="container">
        <h1>Thank you for your business</h1>
        <p>Please click <a href="dashboard" ><u>here</u></a> to go back to <a href="dashboard"><u>your dashboard</u></a></p>
    </section>

    <!-- Older IE Message -->
    <!--[if lt IE 9]>
        <div class="ie-block">
            <h1 class="Ops">Ooops!</h1>
            <p>You are using an outdated version of Internet Explorer, upgrade to any of the following web browser in order to access the maximum functionality of this website. </p>
            <ul class="browsers">
                <li>
                    <a href="https://www.google.com/intl/en/chrome/browser/">
                        <img src="img/browsers/chrome.png" alt="">
                        <div>Google Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.mozilla.org/en-US/firefox/new/">
                        <img src="img/browsers/firefox.png" alt="">
                        <div>Mozilla Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com/computer/windows">
                        <img src="img/browsers/opera.png" alt="">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="http://safari.en.softonic.com/">
                        <img src="img/browsers/safari.png" alt="">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/downloads/ie-10/worldwide-languages">
                        <img src="img/browsers/ie.png" alt="">
                        <div>Internet Explorer(New)</div>
                    </a>
                </li>
            </ul>
            <p>Upgrade your browser for a Safer and Faster web experience. <br/>Thank you for your patience...</p>
        </div>   
    <![endif]-->
</section>

<!-- Javascript Libraries -->
<!-- jQuery -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery.min.js"></script> <!-- jQuery Library -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery-ui.min.js"></script> <!-- jQuery UI -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery.easing.1.3.js"></script> <!-- jQuery Easing - Requirred for Lightbox + Pie Charts-->

<!-- Bootstrap -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/bootstrap.min.js"></script>

<!-- Charts -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.js"></script> <!-- Flot Main -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.time.js"></script> <!-- Flot sub -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.animator.min.js"></script> <!-- Flot sub -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.resize.min.js"></script> <!-- Flot sub - for repaint when resizing the screen -->

<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/sparkline.min.js"></script> <!-- Sparkline - Tiny charts -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/easypiechart.js"></script> <!-- EasyPieChart - Animated Pie Charts -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts.js"></script> <!-- All the above chart related functions -->

<!-- Map -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/maps/jvectormap.min.js"></script> <!-- jVectorMap main library -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/maps/usa.js"></script> <!-- USA Map for jVectorMap -->

<!--  Form Related -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/icheck.js"></script> <!-- Custom Checkbox + Radio -->

<!-- UX -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/scroll.min.js"></script> <!-- Custom Scrollbar -->

<!-- Other -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/calendar.min.js"></script> <!-- Calendar -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/feeds.min.js"></script> <!-- News Feeds -->


<!-- All JS functions -->
<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/functions.js"></script>
</body>
</html>