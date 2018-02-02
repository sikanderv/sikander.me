<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'pear/PHPMailer/src/PHPMailer.php';
require 'pear/PHPMailer/src/Exception.php';

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
// error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// an email address that will be in the From field of the email.
$fromEmail = 'hello@sikander.me';
$fromName = 'Sikander from sikander.me';

// an email address that will receive the email with the output of the form
$sendToEmail = 'sikanderv@gmail.com';
$sendToName = 'Sikki';

// subject of the email
$subject = 'First message from my website';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('name' => 'Name', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message');

// message that will be displayed when everything is OK :)
$okMessage = 'Thank you for sharing your details. You will hear from me shortly!';

// If something goes wrong, we will display this message.
$errorMessage = 'Uh-oh, error while submitting the form. Please try again!';

/*
 *  LET'S DO THE SENDING
 */

try
{

    // if POST array is empty, throw an error, else continue
    if(count($_POST) == 0) throw new \Exception('Form is empty');

    // $emailText = "You have a new message from your website\n=============================\n";
    $emailTextHtml = "<h1>You have a new message from your contact form</h1><hr>";
    $emailTextHtml .= "<table>";

    // Iterate through the POST array
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, append it to the email content
        if (isset($fields[$key])) {
            $emailTextHtml .= "$fields[$key]: $value\n";
        }
    }

    $emailTextHtml .= "</table><hr>";
    $emailTextHtml .= "<p>Have a nice day,<br>Best,<br>Sikander</p>";

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

    $mail->isHTML(true);


    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($sendToEmail, $sendToName); // you can add more addresses by simply adding another line with $mail->addAddress();
    // $mail->addReplyTo($fromEmail);

    $mail->Subject = $subject;

    // This will also create a plain-text version of the HTML email, very handy
    $mail->msgHTML($emailTextHtml);


    if (!$mail->send()) {
      throw new \Exception('I could not send the email.' . $mail->ErrorInfo);
    }

    // Send email
    // mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}

catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
