<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable PHP error display for debugging

include 'connect.php'; // Ensure this contains your database connection code

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

// Function to display styled success or error messages with a card look
function displayMessage($message, $type = 'success') {
    $backgroundColor = $type === 'error' ? '#f8d7da' : '#22c55e'; // Red for error, green for success
    $textColor = $type === 'error' ? '#721c24' : '#ffffff'; // Dark red for error text, white for success text

    echo '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            max-width: 600px;
            background-color: #202221; /* Changed from white to dark */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .message-header {
            background-color: #202221;
            padding: 15px 0;
            color: #ffffff;
        }
        .message-body {
            padding: 20px;
            background-color: ' . $backgroundColor . ';
            color: ' . $textColor . ';
            border-radius: 8px;
            font-size: 16px;
        }
    </style>
    <div class="message-container">
        <div class="message-header">
            <h1>Gainly</h1>
        </div>
        <div class="message-body">
            ' . htmlspecialchars($message) . '
        </div>
    </div>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Registration
        $username = $_POST['username'];
        $email = $_POST['useremail'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];
        $mobilenumber = $_POST['mobilenumber'];

        // Check if passwords match
        if ($password !== $confirmpassword) {
            displayMessage("Passwords do not match.", 'error');
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            displayMessage("Email already registered.", 'error');
            exit();
        }

        $stmt->close();

        // Generate a unique verification token
        $verificationToken = bin2hex(random_bytes(16));

        // Insert user details into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, mobilenumber, token, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $mobilenumber, $verificationToken);

        if ($stmt->execute()) {
            // Send verification email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mutitupeter76@gmail.com'; // Your email address
                $mail->Password   = 'fbwj edrn alpz alur'; // Your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('mutitupeter76@gmail.com', 'Gainly');
                $mail->addAddress($email); // Send to the user's email

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';

                // Email body with professional design
                $mail->Body = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body {
                            background-color: #202221;
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            color: #ffffff;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                        }
                        .email-container {
                            max-width: 600px;
                            background-color: #202221;
                            border-radius: 8px;
                            overflow: hidden;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            padding: 20px;
                            text-align: center;
                            color: #333333;
                        }
                        .email-header {
                            background-color: #202221;
                            padding: 15px 0;
                            color: #ffffff;
                        }
                        .email-body {
                            padding: 20px;
                        }
                        .email-body h2 {
                            margin-bottom: 10px;
                            color: #ffffff;
                        }
                        .email-body p {
                            font-size: 16px;
                            color: #ffffff;
                            margin-bottom: 20px;
                        }
                        .email-footer {
                            font-size: 12px;
                            color: #999999;
                            margin-top: 30px;
                        }
                        .verify-button {
                            background-color: #22c55e;
                            color: #ffffff;
                            text-decoration: none;
                            padding: 12px 25px;
                            font-size: 16px;
                            border-radius: 4px;
                            display: inline-block;
                            margin-top: 20px;
                        }
                        .verify-button:hover {
                            background-color: #1ba34c;
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="email-header">
                            <h1>Gainly</h1>
                        </div>
                        <div class="email-body">
                            <h2>Thank You for Registering!</h2>
                            <p>Dear ' . htmlspecialchars($username) . ',</p>
                            <p>We are excited to have you with us at Gainly. Please click the button below to verify your email and complete your registration.</p>
                            <a href="http://localhost/gainly-ref/verify.php?token=' . $verificationToken . '" class="verify-button">Verify Email</a>
                        </div>
                        <div class="email-footer">
                            <p>If you didn\'t sign up for this account, please ignore this email.</p>
                        </div>
                    </div>
                </body>
                </html>';

                $mail->send();

                // Display styled success message
                displayMessage("A verification email has been sent to your email address. Please check your inbox to verify your account.");
            } catch (Exception $e) {
                displayMessage("Email could not be sent. Mailer Error: {$mail->ErrorInfo}", 'error');
            }

        } else {
            displayMessage("Error: " . $stmt->error, 'error');
        }

        $stmt->close();
    } elseif (isset($_POST['login'])) {
        // Login logic
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            displayMessage("Please fill in all fields.", 'error');
            exit();
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, password, is_verified FROM users WHERE email = ?");
        if (!$stmt) {
            displayMessage("Prepare statement failed: " . $conn->error, 'error');
            exit();
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword, $isVerified);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                if ($isVerified) {
                    $_SESSION['user_id'] = $id;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    displayMessage("Your email address is not verified. Please check your email to verify your account.", 'error');
                }
            } else {
                displayMessage("Incorrect password.", 'error');
            }
        } else {
            displayMessage("No account found with that email address.", 'error');
        }

        $stmt->close();
    }
}
?>
