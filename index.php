<?php 
session_start();
ob_start();
$_SESSION['HtmlSubfolder'] = "/";
$_SESSION['IndexLocation'] = "/log_frog";
$_SESSION['href_location'] = "/";
$_SESSION['HtmlDelimiter'] = "";
$_SESSION['ProjectPath'] = $_SERVER['DOCUMENT_ROOT'] . "/log_frog";
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Truck dispatch services specialized in flatbed trailers</title>
    <meta name="description" content="Our dispatching service model is designed so that forced dispatch is out of the question. We are the # 1 flatbed trailer dispatch service, call today 844-345-3764">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google fonts - (more info: https://www.google.com/fonts) -->
    <link href="//fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <!-- Libs and Plugins CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css"> <!-- bootstrap CSS (more info: http://getbootstrap.com) -->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css"> <!-- Font Icons (more info: http://fortawesome.github.io/Font-Awesome) -->
    <link rel="stylesheet" href="vendor/ytplayer/css/jquery.mb.YTPlayer.min.css"> <!-- YTPlayer CSS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
    <link rel="stylesheet" href="vendor/magnific-popup/css/magnific-popup.css"> <!-- Magnific Popup CSS (more info: http://dimsemenov.com/plugins/magnific-popup/) -->

    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.carousel.css"> <!-- owl carousel CSS (more info: http://www.owlcarousel.owlgraphic.com) -->
    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.theme.default.css"> <!-- owl carousel theme CSS (more info: http://www.owlcarousel.owlgraphic.com) -->

    <!-- Theme navigation menu CSS (more info: http://codyhouse.co/gem/secondary-expandable-navigation) -->
    <link rel="stylesheet" href="css/menu.css">

    <!-- Theme helper classes CSS -->
    <link rel="stylesheet" href="css/helper.css">

    <!-- Theme master CSS -->
    <link rel="stylesheet" href="css/theme.css">

    <!-- Theme custom CSS (all your CSS customizations) -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- GOOGLE MAP DATA FROM https://www.labnol.org/internet/embed-google-maps-background/28457/ -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0PAkIoMfYljc4Hw-NZLHyomYacy8MmmQ&callback=initMap"></script>

    <style>
    #googlemaps {
        height: 100%;
        width: 100%;
        position:absolute;
        top: 0;
        left: 0;
        z-index: 0;
        opacity: .25;
    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- MODAL DOT ROAD CHECK -->
    <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" style="z-index: 10000;">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gridSystemModalLabel">Logistics Frog</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-6">

                <img class="img-responsive" src="/img/logisticsfrog/dot-road-check-en.jpeg" width="792" height="1224">
              </div>
              <div class="col-sm-12 col-md-6">

                <img class="img-responsive" src="/img/logisticsfrog/dot-road-check-es.jpeg" width="792" height="1224">
              </div>
            </div>
          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> -->
    <!-- MODAL DOT ROAD CHECK -->

    <!-- Page preloader (display loading animation while page loads) -->
    <div id="preloader"><div class="pulse"></div></div>

    <!-- Begin page borders -->
    <div class="border-top"></div>
    <div class="border-bottom"></div>
    <div class="border-left"></div>
    <div class="border-right"></div>
    <!-- End page borders -->


    <!-- Begin header -->
    <header class="is-fixed">
      <a id="cd-logo" href=""><img src="img/logo.png?r=<?= date('Gis') ?>" alt="image"></a>
      <nav id="cd-top-nav">
        <!-- <ul> 
          <li><a href="/">Home</a></li>
          <li><a href="#0">Register</a></li>
          <li><a href="#0">Login</a></li>
        </ul> -->
      </nav>
      <!-- Menu trigger (menu button) -->
      <a id="cd-menu-trigger" href="#0"><span class="cd-menu-icon"></span></a>
    </header>
    <!-- End header -->


    <!-- Begin menu  (more info: http://codyhouse.co/gem/secondary-expandable-navigation/) -->
    <nav id="cd-lateral-nav">
      <div class="nav-inner">

        <!-- menu header -->
        <div class="menu-header">Menu</div>

        <!-- Begin single links wrapper -->
        <ul class="cd-navigation cd-single-item-wrapper">
          <li><a href="" class="page-scroll">Home</a></li>
          <li><a href="login.php?language_id=1">Account Login</a></li>
          <li><a href="#section-1" class="page-scroll">Welcome</a></li>
          <li><a href="#section-2" class="page-scroll">Just drive!</a></li>
          <li><a href="#section-3" class="page-scroll">What We Do</a></li>
          <li><a href="#section-8" class="page-scroll">FAQ</a></li>
          <li><a href="#section-11" class="page-scroll">Contact</a></li>
        </ul>
        <!-- End single links wrapper -->

        <div class="menu-separator"></div>

        <!-- Socials icons (replace "http://link.com" widh your own link) -->
        <div class="social-icons">
          <a href="https://www.facebook.com/themetorium" target="_blank"><i class="fa fa-facebook-square"></i></a>
        </div>

      </div>
    </nav>
    <!-- End menu -->


    <!-- Begin body content -->
    <div id="body-content">


      <!-- Begin intro section (Parallax) -->
      <section id="section-intro" class="intro-parallax full-height">

          <!-- Element background image (parallax) -->
          <?php
          # Randomize background
          $second_start = substr(date("s"), 1);
          $second_start == 0 || $second_start == 2 || $second_start == 4 || $second_start == 6 || $second_start == 8 ? $bg = 1 : '';
          $second_start == 1 || $second_start == 3 || $second_start == 5 || $second_start == 7 || $second_start == 9 ? $bg = 3 : '';
          ?>

          <!-- Hide on extra small and small devices -->
          <div class="full-cover bg-image hidden-xs hidden-sm" data-stellar-ratio="0.2" style="background-image: url(img/intro/logistics-frog-home-<?= $bg ?>.jpg);"></div>

          <!-- Hide on medium and large devices -->
          <div class="full-cover bg-image hidden-md hidden-lg" data-stellar-ratio="0.2" style="background-image: url(img/intro/logistics-frog-home-sm-1.jpg);"></div>

          <!-- Element cover -->
          <div class="cover"></div>
          
          <!-- Intro caption -->
          <div class="intro-caption text-white" data-stellar-ratio="0.6">

            <!-- Hide on extra small and small devices -->
            <h1 class="hidden-xs hidden-sm" style="font-size: 65px"><span class="text-white" style="background-color: #05c116;">Logistics </span>Frog</h1>

            <!-- Hide on medium and large devices -->
            <h1 class="hidden-md hidden-lg" style="font-size: 55px"><span class="text-white" style="background-color: #05c116;">Logistics </span>Frog</h1>

            <p>Where trucking meets innovation!</p>
          </div>

          <!-- Made with love :) -->
          <div class="made-with-love hidden-xs" data-stellar-ratio="0.2">
            
            <p class="text-white" style="font-size: 16px; line-height: 12px"><b>Flatbed Dispatch services</b></p>
            <p class="text-white" style="font-size: 16px; line-height: 12px"><b>844-345-3764</b></p>
            <p class="text-white" style="font-size: 16px; line-height: 12px"><b>paperwork@logisticsfrog.com</b></p>
            <p class="text-white" style="font-size: 16px; line-height: 12px"><b>Hablamos Español</b></p>
          </div>

          <!-- Scroll down arrow -->
          <a class="scroll-down-arrow page-scroll text-center" href="#section-1"><span class="text-white"><i class="fa fa-arrow-down"></i></span></a>

      </section>
      <!-- End intro section (Parallax) -->


      <!-- Section 1 -->
      <section id="section-1" class="welcome bg-dark text-white">
        <div class="container">
          <div class="row">

            <div id="googlemaps"></div>
            
            <div class="col-left col-md-12" style="z-index: 1;">

              <div class="col-inner">

                <!-- Begin Heading -->
                <div class="heading heading-xlg">
                  <h2 class="text-center">
                    <span class="text-white"><span style="color: #05c116">Dispatching</span> and </span>
                    <span class="text-white">back office services</span>
                  </h2>
                </div>
                <!-- End Heading -->

                <p class="lead text-center"><b>for <span style="color: #05c116">flatbed</span> carriers across the U.S.</b></p>

              </div> <!-- /.col-inner -->
            </div> <!-- /.col -->

          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>


      <!-- Section 2 -->
      <section id="section-2" class="team split-box no-padding">
        <div class="container-fluid">
          <div class="row">

            <!-- Begin responsive columns of same height (more info: http://www.minimit.com/articles/solutions-tutorials/bootstrap-3-responsive-columns-of-same-height) -->
            <div class="row-same-height"> 

              <div class="col-left col-lg-6 col-lg-height no-padding">
                <img src="img/team.jpg?r=<?= date('Gis') ?>" alt="image">
              </div> <!-- /.col -->

              <div class="col-right col-lg-6 col-lg-height col-middle no-padding">
                <div class="col-inner">

                  <!-- Begin Heading -->
                  <div class="heading heading-xlg">
                    <h1 id="fod_text"><span class="bg-dark text-white">Focus</span><span class="bg-main text-white"> ON DRIVING</span></h1>
                  </div>
                  <!-- End Heading -->

                  <p class="lead text-justify">Our Flatbed dispatch services are designed around you, so you <span class="text-main">don't</span> have to <span class="text-main">worry about anything but driving</span>. Our team of professionals will take care of the rest!</p>

                  <p>Scroll down for a list of services.</p>

                </div> <!-- /.col-inner -->
              </div> <!-- /.col -->
            </div>
            <!-- End responsive columns of same height -->

          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>


      <!-- Section 3 -->
      <section id="section-3" class="work split-box bg-image" style="background-image: url(img/work-bg.jpg);">

        <!-- element cover -->
        <div class="cover"></div>

        <div class="container-fluid text-white">
          <div class="row">

            <!-- Begin responsive columns of same height (more info: http://www.minimit.com/articles/solutions-tutorials/bootstrap-3-responsive-columns-of-same-height) -->
            <div class="row-same-height"> 

              <div class="col-left col-lg-6 col-lg-height">
                <div class="col-inner">

                  <!-- Begin Heading -->
                  <div class="heading heading-xlg">
                    <h1><span class="bg-white text-dark">Our</span><span class="bg-main text-white">Services</span></h1>
                  </div>
                  <!-- End Heading -->

                  <p class="lead text-justify">Our dispatching service model is designed so that <b class="text-main">FORCED DISPATCH IS OUT OF THE QUESTION</b>. Our team of professional flatbed dispatchers will prospect the best options for you while you drive, and if you're not satisfied with the load option we're offering you, you can feel free to request a better alternative and so on until we find that one load you are looking for.</p>

                  <p class="text-center" style="font-size: 23px;">
                    <b>With only <span class="text-main">$175 per truck / per week</span> you'll get:</b>
                  </p>

                  <!-- Begin info box -->
                  <div class="info-box-wrapper">
                    <div class="row">

                      <!-- info box 1 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-paint-brush"></i></span>
                          <div class="info-box-heading">
                            <h3>Load booking and Dispatching</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">We are here to negotiate the highest paying rates in the market and once you have agreed to book a load our friendly staff will coordinate with the freight broker or direct shipper to have that load tendered directly to your company name. The information will then be uploaded into our dispatching software and you will receive directions and assistance from our staff.</p>
                          </div>
                        </div>
                      </div>

                      <!-- info box 2 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-briefcase"></i></span>
                          <div class="info-box-heading">
                            <h3>Back office</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">We won't let you down, our admin department will run credit checks, request certificates of insurance, take care of your diesel cards and everything you need in order to keep you going with no hassle.</p>
                          </div>
                        </div>
                      </div>

                      <!-- info box 3 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-industry"></i></span>
                          <div class="info-box-heading">
                            <h3>New company set up</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">We know you have no time to fill out those long and sometimes confusing carrier packets, so don't worry about it, we'll do it for you.</p>
                          </div>
                        </div>
                      </div>

                    </div> <!-- /.row -->
                  </div>
                  <!-- End info box -->

                </div> <!-- /.col-inner -->
              </div> <!-- /.col -->

              <div class="col-right col-lg-6 col-lg-height">
                <div class="col-inner">

                  <!-- Begin info box -->
                  <div class="info-box-wrapper">
                    <div class="row">

                      <!-- info box 4 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-university"></i></span>
                          <div class="info-box-heading">
                            <h3>Factoring company set ups</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">New in the business or tired of your old factoring company? Don't worry, we'll give you plenty of options for you to choose from. We will suggest some of the strongest factoring companies out there and we will let you know their pros and cons. Remember, it is your business so at the end the choice is yours.</p>                                                        
                          </div>
                        </div>
                      </div>

                      <!-- info box 3 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-truck"></i></span>
                          <div class="info-box-heading">
                            <h3>Over the road support</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">We're hoping for everything to go well from point A to point B, but if something happens while on transit, you know you can count on us, for this particular aspect of the business our support is 24/7 </p>
                            <p class="text-justify">Say you got a flat tire or things got out of control and there was a hazmat spill, we will be there to help you keep on going, we will do anything from finding the nearest shop where you can take care of your broken unit or even contact lawyers, fill out insurance claims and follow-up until your issue gets resolved. In other words, <b class="text-main">we got your back!</b></p>
                          </div>
                        </div>
                      </div>

                      <!-- info box 4 -->
                      <div class="col col-sm-12 col-lg-12">
                        <div class="info-box">
                          <span class="info-box-icon"><i class="fa fa-black-tie"></i></span>
                          <div class="info-box-heading">
                            <h3>Professionalism</h3>
                            <div class="divider"></div>
                          </div>
                          <div class="info-box-info">
                            <p class="text-justify">We make sure your company's paperwork meets the highest standards so your clients know they're dealing with professionals, they will receive well-designed invoices, company letterhead documents when needed, well-structured emails and professional customer service from our contact center.</p>
                          </div>
                        </div>
                      </div>

                    </div> <!-- /.row -->
                  </div>
                  <!-- End info box -->

                </div> <!-- /.col-inner -->
              </div> <!-- /.col -->
            </div>
            <!-- End responsive columns of same height -->

          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>


      <!-- Section 6 -->
      <section id="section-6" class="portfolio no-padding-bottom">
        <div class="container-fluid">
          <div class="row">

            <!-- Begin masonry -->
            <div class="masonry popup-gallery">

              <!-- Begin portfolio heading -->
              <div class="box col-md-4 col-sm-6">
                <div class="box-inner portfolio-heading">
                  <a href="#" target="_blank" class="portfolio-link"></a>
                  <div class="cover"></div>
                  <img src="img/portfolio/portfolio-heading-img.jpg" alt="image">
                  <!-- Begin Heading -->
                  <div class="heading heading-md align-center">
                    <h1><span class="bg-main text-white">Logistics</span>Frog</h1>
                    <p><img src="img/logo.png?r=<?= date('Gis') ?>" alt="image"></p>
                  </div>
                  <!-- End Heading -->
                </div>
              </div>
              <!-- End portfolio heading -->

              <!-- Begin portfolio grid box 1 -->
              <div class="box box-1 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-1.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>

              </div>
              <!-- End portfolio grid box 1 -->

              <!-- Begin portfolio grid box 2 -->
              <div class="box box-2 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-2.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>
              </div>
              <!-- End portfolio grid box 2 -->

              <!-- Begin portfolio grid box 3 -->
              <div class="box box-3 col-md-4 col-sm-6">
                <div class="box-inner">
                    <div class="cover"></div>
                    <img class="thumbnail" src="img/portfolio/portfolio-img-3.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>
              </div>
              <!-- End portfolio grid box 3 -->

              <!-- Begin Portfolio grid box 4 -->
              <div class="box box-4 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-4.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>

              </div>
              <!-- End portfolio grid box 4 -->

              <!-- Begin portfolio grid box 5 -->
              <div class="box box-5 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-5.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>
              </div>
              <!-- End portfolio grid box 5 -->

              <!-- Begin portfolio grid box 6 -->
              <div class="box box-6 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-6.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>
              </div>
              <!-- End portfolio grid box 6 -->

              <!-- Begin portfolio grid box 7 -->
              <div class="box box-7 col-md-4 col-sm-6">
                <div class="box-inner">
                  <div class="cover"></div>
                  <img class="thumbnail" src="img/portfolio/portfolio-img-7.jpg?r=<?= date('Gis') ?>" alt="image">
                </div>
              </div>
              <!-- End portfolio grid box 7 -->

            </div>
            <!-- End masonry -->
                
          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>

      <!-- Section 8 -->
      <section id="section-8" class="faq">
        <div class="container">
          <div class="row">

            <div class="col col-lg-12">
              <div class="col-inner">

                <!-- Begin Heading -->
                <div class="heading heading-xlg heading-center">
                  <h1>FAQ</h1>
                </div>
                <!-- End Heading -->

                <p class="lead text-center">Below you will find some of the most frequently asked questions we receive from our clients, please feel free to go through them or give us a call for more info - <i class="text-main">844-345-3764</i>.</p>

                <!-- Begin accordion -->
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-1">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                          <span class="vertical-align-center">#1</span> What do I need to start working with Logistics Frog?
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-1">
                      <div class="panel-body">
                        
                        <p><i class="fa fa-check"></i> A copy of your Motor Carrier Authority form.</p>
                        <p><i class="fa fa-check"></i> A W9 Form</p>
                        <p><i class="fa fa-check"></i> Proof of insurance showing a minimum of $1,000,000 in Automobile liability and $100,000 in Cargo Insurance.</p>

                        <p>With this documentation at hand, we can start looking for the best paying loads for you.</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-2">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                          <span class="vertical-align-center">#2</span> What kind of trailers do you work with?
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
                      <div class="panel-body">

                        <p>We're specialized in dispatching Flatbeds, Step Decks and hot shot trailers.</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-3">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                          <span class="vertical-align-center">#3</span> Can I decline a load?
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-3">
                      <div class="panel-body">
                        <p>Sure you can, don't forget you're the boss!</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-4">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                          <span class="vertical-align-center">#4</span> Am I required to have my own authority to work with Logistics Frog?
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-4">
                      <div class="panel-body">
                        <p>Yes, you will need to either have your own Authority or be leased to a company that does.</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-5">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                          <span class="vertical-align-center">#5</span> Am I required to sign a long term contract to use your services?
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-5">
                      <div class="panel-body">
                        <p>Not at all, you can stay with us for as long as you like, you are in charge!</p>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- End accordion -->

              </div> <!-- /.col-inner -->
            </div> <!-- /.col -->

          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>

      <!-- Section 11 -->
      <section id="section-11" class="page contact-section bg-dark text-white bg-image" style="background-image: url(img/map-bg.jpg);">

        <!-- Element cover -->
        <div class="cover"></div>

        <div class="container">
          <div class="row">

            <div class="col-left col-md-6">
              <div class="col-inner">

                <!-- Begin Heading -->
                <div class="heading heading-xlg">
                  <h1>Contact <span class="bg-main text-white">Us</span></h1>
                </div>
                <!-- End Heading -->

                <div class="contact-info">
                  <p><i class="fa fa-phone"></i> Phone: 844-345-3764</p>
                  <p><i class="fa fa-envelope"></i> <a href="mailto:paperwork@logisticsfrog.com">paperwork@logisticsfrog.com</a></p>
                </div>

              </div> <!-- /.col-inner -->
            </div> <!-- /.col -->

            <div class="col-right col-md-3">
              <div class="col-inner">

                <!-- Socials icons (replace "http://link.com" widh your own link) -->
                <div class="social-icons">
                  <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                </div>

                <a href="mailto:paperwork@logisticsfrog.com" target="_blank" class="btn btn-danger btn-rounded btn-lg">say hello <i class="fa fa-paper-plane-o"></i></a>

              </div> <!-- /.col-inner -->
            </div> <!-- /.col -->

            <div class="col-right col-md-3">
              <div class="col-inner">

                <a href="<?= $_SESSION['href_location'] ?>terms-and-conditions" class="btn btn-link">Terms and Conditions</a>

                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                  
                  <input type="hidden" name="cmd" value="_s-xclick">
                  <input type="hidden" name="hosted_button_id" value="6G79FJJCE5M3S">
                  
                  <table style="margin-bottom: 5px;">
                    <tr>
                      <td>
                        <input type="hidden" name="on0" value="Service fees">Service fees
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <select name="os0" class="form-control">
                          <option value="Full service">Full service $150.00 USD</option>
                          <option value="Back office support">Back office support $100.00 USD</option>
                        </select> 
                      </td>
                    </tr>
                  </table>
                  <input type="hidden" name="currency_code" value="USD">
                  <input type="image" src="https://www.paypalobjects.com/es_XC/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                  <img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
                </form>

              </div> <!-- /.col-inner -->
            </div> <!-- /.col -->

          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </section>


      <!-- Footer -->
      <footer id="footer" class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="copyright small">
                <a href="#">Inversiones Cerro Modesto S.A.</a>
                <p>Copyright © All Rights Reserved</p>
              </div>
            </div>
          </div>
        </div>
      </footer>

    </div> <!-- /.body-content -->

    <!-- Scroll to top button -->
    <a href="#" class="scrolltotop hidden-xs"><i class="fa fa-arrow-up"></i></a>

    <!-- Libs and Plugins JS -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/jquery/jquery-1.11.1.min.js"></script> <!-- jquery JS (more info: https://jquery.com) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/bootstrap/js/bootstrap.min.js"></script> <!-- bootstrap JS (more info: http://getbootstrap.com) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/jquery.easing.min.js"></script> <!-- jquery easing JS (more info: http://gsgd.co.uk/sandbox/jquery/easing) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/jquery.stellar.min.js"></script> <!-- parallax JS (more info: http://markdalgleish.com/projects/stellar.js) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/smoothscroll.js"></script> <!-- smoothscroll JS (more info: https://gist.github.com/theroyalstudent/4e6ec834be19bf077298/) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/jquery.counterup.min.js"></script> <!-- counter up JS (requires jQuery "waypoints.js" plugin. more info: https://github.com/bfintal/Counter-Up) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/waypoints.min.js"></script> <!-- counter up JS (more info: https://github.com/bfintal/Counter-Up) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/ytplayer/js/jquery.mb.YTPlayer.min.js"></script> <!-- YTPlayer JS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/magnific-popup/js/jquery.magnific-popup.min.js"></script> <!-- Magnific Popup JS (more info: http://dimsemenov.com/plugins/magnific-popup/) -->

    <script src="<?= $_SESSION['href_location'] ?>vendor/masonry.pkgd.min.js"></script> <!-- masonry JS (more info: http://masonry.desandro.com/) -->
    <script src="<?= $_SESSION['href_location'] ?>vendor/imagesloaded.pkgd.min.js"></script> <!-- imagesloaded JS (more info: http://masonry.desandro.com/appendix.html#imagesloaded) -->

    <script src="<?= $_SESSION['href_location'] ?>vendor/owl-carousel/js/owl.carousel.js"></script> <!-- owl carousel JS (more info: http://www.owlcarousel.owlgraphic.com) -->

    <!-- Theme JS -->
    <script src="<?= $_SESSION['href_location'] ?>js/theme.js"></script>

    <!-- Theme custom JS (all your JS customizations) -->
    <script src="<?= $_SESSION['href_location'] ?>js/custom.js"></script>

    <script>

      /*setTimeout(function () {
        
        $('#myModal').modal('show');
      }, 7000);*/

      var position = [40.728458, -74.274455];

      function initialize() {

        var myOptions = {
          zoom: 10,
          streetViewControl: true,
          scaleControl: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('googlemaps'),
            myOptions);


        latLng = new google.maps.LatLng(position[0], position[1]);

        map.setCenter(latLng);

        marker = new google.maps.Marker({
          position: latLng,
          map: map,
          draggable: false,
          animation: google.maps.Animation.DROP
        });
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </body>

</html>