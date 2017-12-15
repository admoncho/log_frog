<?php 
session_start();
ob_start();
### NOTES BEFORE GOING LIVE ###
# $_POST['errorCode'] == 54 is not returned on any of the tests in the 
# documentation, we need to ask for a full list of Production environment
# response codes.
# 

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Remove leading zeros
$invoice_id = ltrim($_POST['purchaseOperationNumber'], '0');

# If $_POST['errorCode'] == 2202 || 2300 || 1101 || 2401 || 57 create new 
# duplicated invoice and set this one as canceled.
if ($_POST['errorCode'] == 2202 
  || $_POST['errorCode'] == 2300 
  || $_POST['errorCode'] == 1101 
  || $_POST['errorCode'] == 2401
  || $_POST['errorCode'] == 57 
  || $_POST['errorCode'] == 54 
  || $_POST['errorCode'] == 05 
  || $_POST['errorCode'] == 14
  || $_POST['errorCode'] == 15 
  || $_POST['errorCode'] == 2319) {

  # Display error message
  $display_error_message = 1;
  
  # Get this invoice data
  $return_invoice = DB::getInstance()->query("SELECT client_id, week_number FROM invoice WHERE id = " . $invoice_id);

  foreach ($return_invoice->results() as $return_invoice_data) {
    
    # Create new duplicated invoice
    $insert = DB::getInstance()->query("
      INSERT INTO invoice (client_id, week_number) 
      VALUES (" . $return_invoice_data->client_id . ", " . $return_invoice_data->week_number . ")");

    $last_invoice_id = $insert->last();

    if ($insert->count()) {
      
      # Get invoice items
      $return_invoice_item = DB::getInstance()->query("SELECT * FROM invoice_item WHERE invoice_id = " . $invoice_id);

      foreach ($return_invoice_item->results() as $return_invoice_item_data) {
        
        # Add duplicates
        $insert_item = DB::getInstance()->query("
          INSERT INTO invoice_item (invoice_id, description, cost, default_charge) 
          VALUES (" . $last_invoice_id . "
            , '" . $return_invoice_item_data->description . "'
            , " . $return_invoice_item_data->cost . "
            , " . $return_invoice_item_data->default_charge . "
          )
        ");
      }
    }
  }

  $update_rejected = DB::getInstance()->query("
    
    UPDATE invoice 
    
    SET 
      rejected = '" . date('Y-m-d G:i:s') . "', 
      errorCode = '" . $_POST['errorCode'] . "', 
      errorMessage = '" . htmlentities($_POST['errorMessage'], ENT_QUOTES) . "', 
      purchaseAmount = '" . ($_POST['purchaseAmount'] / 100) . "', 
      authorizationCode = '" . $_POST['authorizationCode'] . "'

    WHERE id = " . $invoice_id);
}

// Misma clave que se usa para el envio a VPOS2
# THIS FILE IS PRODUCTION ENVIRONMENT ONLY
$claveSecreta = 'SJTsJZHChEBhFwWt_552953787';

// purchaseVerication que devuelve la Pasarela de Pagos
$purchaseVericationVPOS2 = $_POST['purchaseVerification'];

//purchaseVerication que genera el comercio
$purchaseVericationComercio = openssl_digest($_POST['acquirerId'] . $_POST['idCommerce'] . $_POST['purchaseOperationNumber'] . $_POST['purchaseAmount'] . $_POST['purchaseCurrencyCode'] . $_POST['authorizationResult'] . $claveSecreta, 'sha512');

# If $_POST['authorizationCode'] is set and not empty
if (isset($_POST['authorizationCode']) && $_POST['authorizationCode'] != '') {
  
  $update_paid = DB::getInstance()->query("
    
    UPDATE invoice 
    
    SET 
      paid = '" . date('Y-m-d G:i:s') . "', 
      errorCode = '" . $_POST['errorCode'] . "', 
      errorMessage = '" . htmlentities($_POST['errorMessage'], ENT_QUOTES) . "', 
      purchaseAmount = '" . ($_POST['purchaseAmount'] / 100) . "', 
      authorizationCode = '" . $_POST['authorizationCode'] . "'

    WHERE id = " . $invoice_id);

    # Send mail
    $email_to = 'marco@logisticsfrog.com';

    $subject = 'Payment received from ' . $_POST['reserved23'];

    $headers = "From: " . strip_tags('no-reply@logisticsfrog.com') . "\r\n";
    $headers .= "Reply-To: ". strip_tags('no-reply@logisticsfrog.com') . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';
    $message .= $_POST['reserved23'] . ' has sent a payment for an amount of $' . ($_POST['purchaseAmount'] / 100);
    $message .= '<br><br> <a ' .  $_SESSION['href_location'] . 'dashboard/invoice/?invoice_id=' . ltrim($_POST['purchaseOperationNumber'], '0') . '">Click here to see this invoice</a>.';
    $message .= '</body></html>';

    mail($email_to, $subject, $message, $headers); ?>
  
  <script type="text/javascript">
    
    // Redirect user to main invoice page
    window.location = "/dashboard/invoice/";
  </script>
  <?php
}

?>

<div class="row">
  <div class="col-sm-12 col-md-12">

    <div class="panel panel-body">

      <?php 
      
      //Si ambos datos son iguales
      if ($purchaseVericationVPOS2 == $purchaseVericationComercio || $purchaseVericationVPOS2 == "") {

        if (isset($display_error_message)) { ?>
          
          <div class="alert alert-danger" role="alert">

            <i class="fa fa-warning"></i> 
            Something went wrong, the payment was not processed, click <a href="<?= $_SESSION['href_location'] ?>dashboard/invoice/?invoice_id=<?= $last_invoice_id ?>">here</a> to try again.
          </div> <?php
        }
         ?>
  
        <!-- 
        echo '<b>result: </b>' . $_POST['result'] . '<br>';
        echo '<b>errorCode: </b>' . $_POST['errorCode'] . '<br>';
        echo '<b>errorMessage: </b>' . $_POST['errorMessage'] . '<br>';
        echo '<b>operationNumber: </b>' . $_POST['operationNumber'] . '<br>';
        echo '<b>authorizationCode: </b>' . $_POST['authorizationCode'] . '<br>';
        echo '<b>cardNumber: </b>' . $_POST['cardNumber'] . '<br>';
        echo '<b>purchaseAmount: </b>' . $_POST['purchaseAmount'] . '<br>';
        echo '<b>purchaseCurrencyCode: </b>' . $_POST['purchaseCurrencyCode'] . '<br>';
        echo '<b>terminalCode: </b>' . $_POST['terminalCode'] . '<br>';
        echo '<b>authenticationECI: </b>' . $_POST['authenticationECI'] . '<br>';
        echo '<b>cardType: </b>' . $_POST['cardType'] . '<br>';
        echo '<b>language: </b>' . $_POST['language'] . '<br>';
        echo '<b>purchaseIPAddress: </b>' . $_POST['purchaseIPAddress'] . '<br>';
        echo '<b>billingAddress: </b>' . $_POST['billingAddress'] . '<br>';
        echo '<b>billingCity: </b>' . $_POST['billingCity'] . '<br>';
        echo '<b>billingCountry: </b>' . $_POST['billingCountry'] . '<br>';
        echo '<b>billingEmail: </b>' . $_POST['billingEmail'] . '<br>';
        echo '<b>billingFirstName: </b>' . $_POST['billingFirstName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';

        echo '<b>billingPhone: </b>' . $_POST['billingPhone'] . '<br>';
        echo '<b>billingState: </b>' . $_POST['billingState'] . '<br>';
        echo '<b>billingZip: </b>' . $_POST['billingZip'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        echo '<b>billingLastName: </b>' . $_POST['billingLastName'] . '<br>';
        <table>
            <tr><td>AuthorizationResult</td><td><?//php echo $_POST['authorizationResult'];?></td></tr>
            <tr><td>AuthorizationCode</td><td><?//php echo $_POST['authorizationCode'];?></td></tr>
            <tr><td>ErrorCode</td><td><?//php echo $_POST['errorCode'];?></td></tr>
            <tr><td>ErroMessage</td><td><?//php echo $_POST['errorMessage'];?></td></tr>
            <tr><td>Bin</td><td><?//php echo $_POST['bin'];?></td></tr>
            <tr><td>Brand</td><td><?//php echo $_POST['brand'];?></td></tr>
            <tr><td>PaymentReferenceCode</td><td><?//php echo $_POST['paymentReferenceCode'];?></td></tr>
            <tr><td>Número de Operacion</td><td><?//php echo $_POST['purchaseOperationNumber'];?></td></tr>
            <tr><td>Monto</td><td><?//php echo "S/. " . $_POST['purchaseAmount']/100;?></td></tr>
        </table>  -->
      
      <?php
      
      // Si ambos datos son diferentes
      } else {
        
        echo "<h1>Transacción Invalida. Los datos fueron alterados en el proceso de respuesta.</h1>";
      } ?>   

    </div>

  </div>  
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
