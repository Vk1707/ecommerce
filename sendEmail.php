<?php
use PHPMailer\PHPMailer\PHPMailer;
include("./inc/dbclass.php");

//Load Composer's autoloader
require 'shop/assets/PHPMailer/vendor/autoload.php';


if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
  
        $mail = new PHPMailer(true);
        //Server settings
        $mail->isSMTP();                                                  //Send using SMTP
        $mail->Host       = 'mail.hotshoppingdeals.in';                         //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                       //Enable SMTP authentication
        $mail->Username   = 'info@hotshoppingdeals.in';           //SMTP username
        $mail->Password   = 'Modi@2024';                         //SMTP password
        $mail->SMTPSecure = 'TLS';                                   //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

        //Recipients
        $mail->setFrom('info@hotshoppingdeals.in', 'Hot Shopping Deals');
        $mail->addAddress('info@hotshoppingdeals.in', 'Hot Shopping Deals');  
        $mail->addAddress('pcsaini@gmail.com', 'Puran Saini');  

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = "
                <html>
                <head>
                    <style>
                        /* Ensure maximum compatibility for emails */
                        body {
                            margin: 0;
                            padding: 0;
                            width: 100% !important;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }
                        table {
                            border-collapse: collapse;
                        }
                        img {
                            border: 0;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                        }
                    </style>
                </head>
                <body>
                    <table width='100%' cellspacing='0' cellpadding='0' style='background-color: #f4f4f4; margin: 0 auto;'>
                        <tr>
                            <td align='center'>
                                <table width='600' cellspacing='0' cellpadding='0' style='background-color: #ffffff; margin: 20px auto; padding: 20px;'>
                                    <!-- Header -->
                                    <tr>
                                        <td align='center' style='padding: 20px 0; background-color: #07294d;'>
                                            <h1 style='margin: 0; color: white;'>New Contact Form Submit</h1>
                                        </td>
                                    </tr>
                                    <tr style='background-color: #f8f8f8;'>
                                        <td style='padding: 20px; color: #333;'>
                                            <p><strong>Name:</strong> $name</p>
                                            <p><strong>Email:</strong> $email</p>
                                            <p><strong>Subject:</strong> $subject</p>
                                            <p><strong>Message:</strong>$message</p>
                                        </td>
                                    </tr>    
                                    <tr>
                                    <td align='center' style='padding: 20px; background-color: #07294d; color: #ffffff;'>
                                        <p style='margin: 0;'>&copy; 2024 <span> Hot Shopping Deals || </span> Developed by <span> <a href='https://www.ndspl.in' target='_blank' style='color: #ffffff; text-decoration: none;'>NDSPL</a> </span></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </body>
                </html>
        ";
        
        if($mail->send()){
            $url = 'contact.php?status=1';
            gotopage($url);
        }
    } else {
        $url = 'contact.php?status=2';
        gotopage($url);
 }

?>