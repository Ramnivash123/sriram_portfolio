<?php
require_once 'vendor/autoload.php';
use Twilio\Rest\Client;

// Your Twilio credentials
$account_sid = '#';
$auth_token = '#';
$verify_sid = '#';

// Initialize Twilio client
$twilio = new Client($account_sid, $auth_token);

if (isset($_POST['generate_otp'])) {
    // Generate and send OTP
    session_start();
    $mobile = $_POST['mobile'];
    $_SESSION['otp_username'] = $_POST['username']; // Store username
    
    try {
        $verification = $twilio->verify->v2->services($verify_sid)
            ->verifications
            ->create($mobile, "sms");
        
        // Redirect back with success message
        header("Location: index.php?otp_sent=1&mobile=" . urlencode($mobile));
        exit();
    } catch (Exception $e) {
        // Handle error
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} elseif (isset($_POST['verify_otp'])) {
    // Verify OTP
    session_start(); // Start session
    $mobile = $_POST['mobile'];
    $otp_code = $_POST['otp'];
    $username = $_SESSION['otp_username'] ?? ''; // Retrieve stored username
    
    try {
        $verification_check = $twilio->verify->v2->services($verify_sid)
            ->verificationChecks
            ->create([
                "to" => $mobile,
                "code" => $otp_code
            ]);
        
        if ($verification_check->status === "approved") {
            // OTP is valid - proceed with login
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = $_POST['type'];
            $_SESSION['mobile'] = $mobile;
            $_SESSION['username'] = $username;
            
            // Check for specific username and mobile
            if ($username === 'Ramnivash' && $mobile === '+918122155056') {
                header("Location: dashboard.php");
            } else {
                header("Location: dashboard2.php");
            }
            exit();
        } else {
            // OTP is invalid
            header("Location: index.php?error=Invalid OTP&mobile=" . urlencode($mobile) . "&otp_sent=1");
            exit();
        }
    } catch (Exception $e) {
        // Handle error
        header("Location: index.php?error=" . urlencode($e->getMessage()) . "&mobile=" . urlencode($mobile) . "&otp_sent=1");
        exit();
    }
}
?>
