<?php
session_start();
ob_start();
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
    <link href="http://fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <!-- Libs and Plugins CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css"> <!-- bootstrap CSS (more info: http://getbootstrap.com) -->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css"> <!-- Font Icons (more info: http://fortawesome.github.io/Font-Awesome) -->
    <link rel="stylesheet" href="vendor/ytplayer/css/jquery.mb.YTPlayer.min.css"> <!-- YTPlayer CSS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
    <link rel="stylesheet" href="vendor/magnific-popup/css/magnific-popup.css"> <!-- Magnific Popup CSS (more info: http://dimsemenov.com/plugins/magnific-popup/) -->

    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.carousel.min.css"> <!-- owl carousel CSS (more info: http://www.owlcarousel.owlgraphic.com) -->
    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.theme.default.css"> <!-- owl carousel theme CSS (more info: http://www.owlcarousel.owlgraphic.com) -->

    <!-- Theme navigation menu CSS (more info: http://codyhouse.co/gem/secondary-expandable-navigation) -->
    <link rel="stylesheet" href="css/menu.css">

    <!-- Theme helper classes CSS -->
    <link rel="stylesheet" href="css/helper.css">

    <!-- Theme master CSS -->
    <link rel="stylesheet" href="css/theme.css">

    <!-- Theme custom CSS (all your CSS customizations) -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

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
      <a id="cd-logo" href="<?= $_SESSION['href_location'] ?>"><img src="<?= $_SESSION["href_location"] ?>img/logo.png?r=<?= date('Gis') ?>" alt="image"></a>
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
          <li><a href="<?= $_SESSION['href_location'] ?>" class="page-scroll">Home</a></li>
          <li><a href="http://quantum.logisticsfrog.com/login?language_id=1">Account Login</a></li>
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


      <!-- Terms and Conditions -->
      <section class="team split-box">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 col-md-6">
            
            <h1 class="text-center">Terms and Conditions</h1>

            <p class="text-justify"><small>Please read these terms and conditions carefully before using the online payment facility. Using the online payment facility on this website indicates that you accept these terms and conditions .</small></p>

            <h2>About us</h2>

            <p class="text-justify"><small>Logistics Frog is property of Inversiones Cerro Modesto, S.A. which is in Quebradilla, Cartago, Costa Rica; it provides Flatbed dispatch services mainly for Owner Operators throughout the United States and with its no forced dispatch designed model Logistics Frog has been rapidly growing in the industry.</small></p>

            <h2>Use of site and services</h2>

            <p class="text-justify"><small>If you are one of Logistics Frog’s clients you will have a login id and password at https://logisticsfrog.com that will grant you access to all of your company’s documents online; You can also make your weekly payments or use the tools available on the site, although you shall not use this site for any other purposes, including without limitation, to make any speculative, false or fraudulent purchase and or payments. This site and the content provided in this site may not be copied, reproduced, republished, uploaded, posted, transmitted or distributed. ‘Deep-linking’, ’embedding’ or using analogous technology is strictly prohibited. Unauthorized use of this site and/or the materials contained on this site may violate applicable copyright, trademark or other intellectual property laws or other laws.</small></p>

            <p class="text-justify"><small>Logistics Frog’s services are not subject to long term contracts, instead, companies are charged based on the amount of trucks that are served on a per week basis.</small></p>

            <h2>Our Rights</h2>

            <p class="text-justify"><small>We reserve the right to: 1. Remain a third party at all times, meaning that we will not be responsible or liable, directly or indirectly, in any way for any loss or damage of any kind incurred while using the services that we provide. 2. change these Conditions from time to time, and your continued use of Logistics Frog (or any part of) following such change shall be deemed to be your acceptance of such change.</small></p>

            <h2>Privacy Policy</h2>

            <p class="text-justify"><small>We are committed to protecting your privacy. This privacy policy applies to all the web pages related to this https://logisticsfrog.com All the information gathered in any online form on the website or in conversations with any of our agents, is used to be able to provide a better service. The information will not be used for anything other than which is stated in the Terms &amp; Conditions of use for this service. None of the information will be sold or made available to anyone.</small></p>

            <h2>Payment Options and Pricing</h2>

            <p class="text-justify"><small>All transactions will be processed in United States Dollar (US$). The payments can be submitted with a credit or debit card. The cost of the “full service” is US$175.00 per week, per truck. These payments can be made Friday or Saturday of the same week, failure to submit payments on time will generate a US$10 late fee. You can also submit payments via PayPal using the email paperwork@logisticsfrog.com</small></p>

            <h2>Credit and debit Card</h2>

            <p class="text-justify"><small>We accept MasterCard and Visa credit or debit cards. A payment receipt will be sent to our clients once the payment is received.</small></p>

            <h2>Cancelation policy</h2>

            <p class="text-justify"><small>Once a payment has been received, it is non-refundable. If a unit breaks down and that week was already paid the money will be considered an early payment, if one or several weeks were paid in advance and the carrier decides to stop using the services of the company, Logistics Frog will retain 100 % of the amount paid. The company would be on its right to keep on using the services until the paid amount has reached its limits.</small></p>

            <h2>Consent</h2>

            <p class="text-justify"><small>I understand that all the designs and trademarks are registered to Inversiones Cerro Modesto S.A. and I undertake not to copy/duplicate the trademarks and designs directly or indirectly in anyway and understand the legal implications thereof. I accept the Terms and Conditions that are required to use the services of Logistics Frog and I also understand that failure to fulfill those terms will be considered a breach of contract and the services of Logistics Frog will no longer be provided.</small></p>

          </div>

          <div class="col-sm-12 col-md-6">
            
            <h1 class="text-center">T&Eacute;RMINOS Y CONDICIONES</h1>

            <p class="text-justify"><small>Por favor lea los siguientes términos y condiciones cuidadosamente antes de usar los servicios de pago en línea. Utilizar el sistema de pago en línea en esta página web indica su aceptación a estos términos y condiciones.</small></p>

            <h2>Acerca de nosotros</h2>

            <p class="text-justify"><small>Logistics Frog es propiedad de Inversiones Cerro Modesto, S.A., misma que está localizada en Quebradilla, Cartago, Costa Rica, y provee servicios de logística, especializada en pequeñas empresas de transporte terrestre con carretas tipo flatbed y que operan en los Estados Unidos. Con un modelo diseñado para que no exista “forced dispatch” Logistics Frog ha estado creciendo rápidamente en la industria.</small></p>

            <h2>Uso de la página web y los servicios</h2>

            <p class="text-justify"><small>Si es cliente de Logistics Frog va a tener un nombre de usuario y una palabra clave para acceder, desde https://logisticsfrog.com a toda la información de su empresa en línea; También podrá hacer sus pagos semanales o utilizar las herramientas disponibles en el sitio web, pero por ningún motivo deberá utilizar este sitio para, eso incluye, pero no se limita a, hacer especulaciones o compras y/o pagos fraudulentos. Este sitio y su contenido no pueden ser copiados, reproducidos, re-publicados, subidos, transmitidos o distribuidos. “Enlaces profundos”, “integraciones” o el uso de tecnologías análogas son estrictamenteprohibidas. El uso no autorizado de este sitio y/o de los materiales contenidos en el sitio pueden violar leyes de derechos de autor, de marca u otras leyes de propiedad intelectual aplicables.</small></p>

            <p class="text-justify"><small>Nuestros servicios no están sujetos a contratos de larga duración, en lugar de eso, las empresas pagan basadas en la cantidad de camiones con los que se trabajó esa semana.</small></p>

            <h2>De nuestros Derechos</h2>

            <p class="text-justify"><small>Nos reservamos el derecho de: 1. Permanecer como un tercero en todo momento, y por ende no aceptamos responsabilidad alguna ni de ninguna manera por alguna pérdida o daño que de cualquier manera pueda haber sido resultado de utilizar alguno de nuestros servicios. 2. Hacer actualizaciones en estas condiciones cuando sea necesario y su uso ininterrumpido de los servicios de Logistics Frog será considerado como aceptación a los cambios o actualizaciones.</small></p>

            <h2>Política de privacidad</h2>

            <p class="text-justify"><small>Estamos comprometidos en proteger su privacidad, esta política de privacidad aplica para todas las páginas relacionadas a https://logisticsfrog.com</small></p>

            <p class="text-justify">Toda la información recogida en los formularios de la página web o en las entrevistas con los agentes de servicio será usada para brindarle un mejor servicio. Esta información no será vendida para ningún otro fin más que para el establecido en estos Términos y Condiciones. Ninguna de la información será vendida o expuesta a ningún tercero.</p>

            <h2>Precio y opciones de pago</h2>

            <p class="text-justify"><small>Todas las transacciones serán procesadas en Dólares Estadounidenses.; El costo del paquete completo es de US$175.00 semanal por camión. El cobro se hará los viernes y se deberá pagar ese mismo día o inclusive el día sábado, la no realización del pago en tiempo generará un cobro extra de US$10 por semana, por camión. También puede hacer sus pagos a través de PayPal, utilizando el correo logisticsfrog.com</small></p>

            <h2>Tarjetas de crédito y débito</h2>

            <p class="text-justify"><small>Aceptamos tarjetas de débito y crédito Visa y Master Card. Una vez recibido el pago un correo de confirmación será enviado al cliente.</small></p>

            <h2>Política de cancelaciones</h2>

            <p class="text-justify"><small>Una vez que un pago ha sido recibido, este no es reembolsable, Si una unidad tiene algún desperfecto o no puede trabajar una semana y esta ya había sido pagada, el dinero se mantendrá como pago adelantado para cuando dicha unidad vuelva a trabajar. Si una empresa decide romper relaciones con Logistics Frog y esta había hecho pagos por adelantado, Logistics Frog se quedará con el 100 % de la suma cancelada. La empresa tendrá el derecho a seguir utilizando los servicios hasta que el monto pagado haya llegado a su límite.</small></p>

            <h2>Consentimiento</h2>

            <p class="text-justify"><small>Entiendo que las marcas Logistics Frog y logisticsfrog.com son propiedad de Inversiones Cerro Modesto S.A. y queda prohibido copiar la tipografía, logos o diseños encontrados en el sitio web. Acepto los Términos y Condiciones establecidos para el uso de los servicios y comprendo que el no cumplimiento departe de la empresa de transporte significará una ruptura contractual y por ende el cese en los servicios por parte de Logistics Frog.</small></p>

          </div>
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
    <script src="vendor/jquery/jquery-1.11.1.min.js"></script> <!-- jquery JS (more info: https://jquery.com) -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script> <!-- bootstrap JS (more info: http://getbootstrap.com) -->
    <script src="vendor/jquery.easing.min.js"></script> <!-- jquery easing JS (more info: http://gsgd.co.uk/sandbox/jquery/easing) -->
    <script src="vendor/jquery.stellar.min.js"></script> <!-- parallax JS (more info: http://markdalgleish.com/projects/stellar.js) -->
    <script src="vendor/smoothscroll.js"></script> <!-- smoothscroll JS (more info: https://gist.github.com/theroyalstudent/4e6ec834be19bf077298/) -->
    <script src="vendor/jquery.counterup.min.js"></script> <!-- counter up JS (requires jQuery "waypoints.js" plugin. more info: https://github.com/bfintal/Counter-Up) -->
    <script src="vendor/waypoints.min.js"></script> <!-- counter up JS (more info: https://github.com/bfintal/Counter-Up) -->
    <script src="vendor/ytplayer/js/jquery.mb.YTPlayer.min.js"></script> <!-- YTPlayer JS (more info: https://github.com/pupunzi/jquery.mb.YTPlayer) -->
    <script src="vendor/magnific-popup/js/jquery.magnific-popup.min.js"></script> <!-- Magnific Popup JS (more info: http://dimsemenov.com/plugins/magnific-popup/) -->

    <script src="vendor/masonry.pkgd.min.js"></script> <!-- masonry JS (more info: http://masonry.desandro.com/) -->
    <script src="vendor/imagesloaded.pkgd.min.js"></script> <!-- imagesloaded JS (more info: http://masonry.desandro.com/appendix.html#imagesloaded) -->

    <script src="vendor/owl-carousel/js/owl.carousel.min.js"></script> <!-- owl carousel JS (more info: http://www.owlcarousel.owlgraphic.com) -->

    <!-- Theme JS -->
    <script src="js/theme.js"></script>

    <!-- Theme custom JS (all your JS customizations) -->
    <script src="js/custom.js"></script>
  </body>

</html>