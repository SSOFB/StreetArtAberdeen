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
$message = $input->get('message', '', 'string');
$send = $input->get('send', false, 'boolean');

# find the currect URL
$uri = Uri::getInstance();
$url = $uri->toString();

if ( !$send ){
    # display the form

    # get the params we need
    $preamble = $params->get('preamble');
    ?>

<form action="<?php echo $uri; ?>" method="post" class="simplecontactform">
    <?php
    if ( strlen($preamble) ) {
        echo "<p>" . $preamble . "</p>";
    }
    ?>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value=""><br>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value=""><br>
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value=""><br>
    </div>
    <div>
        <label for="Message">Message:</label>
        <textarea id="message" name="message"> </textarea><br>
    </div>
    <div class="send_box">
        <input type="hidden" name="send" value="true">
        <input type="submit" value="Send"><br><br>
</form> 

    <?php

} else {
    # process the form

    # get the params we need
    $subject = $params->get('subject');
    $message_footer = $params->get('message_footer');
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
    $body .= "Phone: " . $phone . "\n";
    $body .= "Subject: " . $subject . "\n";
    $body .= "Message: " . $message . "\n";
    $body .= "\n";
    $body .= "Form on page: " . $uri . "\n";
    $body .= "\n";
    $body .= $message_footer;

    $mailer->setSubject('Message from the website');
    $mailer->setBody($body);
    
    $send = $mailer->Send();
    if ( $send !== true ) {
        echo 'Error sending message: ';
    } else {
        echo 'Thanks for getting in touch';
    }

}
