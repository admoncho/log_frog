<?php

if ($_GET['_hp_update_invoice']) {
	$update = DB::getInstance()->query("UPDATE invoice SET status = 1, paid = '" . date('Y-m-d G:i:s') . "' WHERE data_id = " . $_GET['invoice_id']);
	$update->count() ? Session::flash('update_invoice', 'Invoice marked as paid successfully') . Redirect::to('invoicing?invoice_id=' . $_GET['invoice_id']) : Session::flash('update_invoice_error', $_QC_language[16]) ;
}
