<?php

// Very small helper to send a verification email.
// Adjust mailbox headers or replace with PHPMailer if needed.
function send_verification_email($email, $token)
{
    $subject = 'Xác thực tài khoản - QuanLyTour';
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? rtrim($_SERVER['HTTP_ORIGIN'], '/') : '';
    $verifyLink = $origin ? $origin . '/verify.php?token=' . urlencode($token) : '/verify.php?token=' . urlencode($token);

    $message = "<p>Xin chào,</p>" .
               "<p>Vui lòng nhấp vào liên kết bên dưới để xác thực tài khoản của bạn:</p>" .
               "<p><a href=\"{$verifyLink}\">{$verifyLink}</a></p>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: no-reply@quanlytour.local\r\n";

    // mail() may not be configured on local environment; return boolean as sent flag.
    return @mail($email, $subject, $message, $headers);
}
