<?php
/**
 * Simple Contact Form
 * Module Entry Point
 * 
 * @package    SSOFB.Modules
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       https://github.com/SSOFB/very_simple_joomla_contact_form_module
 * mod_simplecontactform is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

# No direct access
defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();

# get the form input
$input = $app->input;
$name = $input->get('name', '', 'string');
$email = $input->get('email', '', 'string');
$phone = $input->get('phone', '', 'string');
$addres = $input->get('address', '', 'string');
$timeslot = $input->get('timeslot', '', 'string');
$subject = $input->get('subject', '', 'string');
$message = $input->get('message', '', 'string');
$send = $input->get('send', false, 'boolean');

# find the currect URL
$uri = Uri::getInstance();
$url = $uri->toString();

if ( !$send ){
    # display the form
    ?>
<style type='text/css'>
    .simplecontactform label {
        width: 200px;
    }
</style>

<form action="<?php echo $uri; ?>" method="post" class="simplecontactform">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" value=""><br>

  <label for="email">Email:</label>
  <input type="text" id="email" name="email" value=""><br>

  <label for="phone">Phone:</label>
  <input type="text" id="phone" name="phone" value=""><br>

  <label for="address">Address:</label>
  <input type="text" id="address" name="address" value=""><br>

  <label for="timeslot">Prefered delivery / collection time slot:</label>
  <input type="text" id="timeslot" name="timeslot" value=""><br>

  <label for="subject">Subject:</label>
  <input type="text" id="subject" name="subject" value=""><br>

  <label for="Message">Message:</label>
  <textarea id="message" name="message"> </textarea><br>

  <input type="hidden" name="send" value="true">
  <input type="submit" value="Send"><br><br>
</form> 

    <?php

} else {
    # process the form

    # recipient from the form settings
    $recipient = $params->get('recipient');

    # sender
    $config = JFactory::getConfig();
    $sender = array( 
        $config->get( 'mailfrom' ),
        $config->get( 'fromname' ) 
    );
    
    $mailer = JFactory::getMailer();
    $mailer->setSender($sender);
    $mailer->addRecipient($recipient);
    
    $body   = "Message from the simple contact form module \n";
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Address: " . $address . "\n";
    $body .= "Time slot: " . $timeslot . "\n";
    $body .= "Subject: " . $subject . "\n";
    $body .= "Message: " . $message . "\n";
    $body .= "\n";
    $body .= "URL: " . $uri . "\n";
 
    $mailer->setSubject('Message from the website');
    $mailer->setBody($body);
    
    $send = $mailer->Send();
    if ( $send !== true ) {
        echo 'Error sending email: ';
    } else {
        echo 'Mail sent';
    }

}
