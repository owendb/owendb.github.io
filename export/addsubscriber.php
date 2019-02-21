<?php

use \DrewM\MailChimp\MailChimp;

/**
 * @file
 * Simple callback for js to add subscriber to a single mailchimp list.
 */

require __DIR__ . '/vendor/autoload.php';

$config = parse_ini_file('config.php');

$MailChimp = new MailChimp($config['api_key']);

$email = isset($_POST['email'])? $_POST['email'] : FALSE;
$fname = isset($_POST['fname'])? $_POST['fname'] : '';
$lname = isset($_POST['lname'])? $_POST['lname'] : '';

if ($email) {
  $result = $MailChimp->post('lists/' . $config['list_id'] . '/members', [
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields' => ['FNAME'=>$fname, 'LNAME'=>$lname],
            ]);
  if ($MailChimp->success()) {
    // subscribed successfully
    http_response_code(200);
    echo 'success';
  } else {
    // subscription failed for some reason
    http_response_code(400);
    echo $MailChimp->getLastError();
  }
} else {
  http_response_code(400);
  echo 'no email provided';
}

?>
