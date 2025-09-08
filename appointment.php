<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');

// IMPORTANT: On Bluehost, if mobile still fails with a tiny script in response,
// ask support to whitelist POST to /appointment.php (ModSecurity/Imunify360).

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request.']);
  exit;
}

// Read POSTed fields (match your form)
$u_name  = trim($_POST['u_name']  ?? '');
$u_email = trim($_POST['u_email'] ?? '');
$phone   = trim($_POST['phone']   ?? '');
$doctor  = trim($_POST['doctor']  ?? '');
$msg     = trim($_POST['message'] ?? '');

if ($u_name === '' || $u_email === '' || $doctor === '') {
  echo json_encode(['success' => false, 'message' => 'Please provide your name, email, and select a doctor.']);
  exit;
}
if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
  exit;
}

/*
 * If you want to send via AWS SES, uncomment and set the correct paths:
 *
 * require __DIR__ . '/vendor/vendor/autoload.php';
 * $config = require __DIR__ . '/vendor/config.php';
 * use Aws\Ses\SesClient;
 * use Aws\Exception\AwsException;
 *
 * $ses = new SesClient([
 *   'version' => 'latest',
 *   'region'  => $config['aws']['region'],
 *   'credentials' => [
 *     'key'    => $config['aws']['key'],
 *     'secret' => $config['aws']['secret'],
 *   ],
 * ]);
 *
 * $subject = 'New Appointment Request - ' . $doctor;
 * $body    = "New appointment request:\n\n"
 *          . "Name: {$u_name}\n"
 *          . "Email: {$u_email}\n"
 *          . "Phone: " . ($phone ?: 'N/A') . "\n"
 *          . "Doctor: {$doctor}\n"
 *          . "Message:\n{$msg}\n";
 *
 * $from = 'asquaremailer@gmail.com';     // verified in SES
 * $to   = 'elavarasan5193@gmail.com';   // verified if SES sandbox
 *
 * try {
 *   $result = $ses->sendEmail([
 *     'Destination' => ['ToAddresses' => [$to]],
 *     'Message' => [
 *       'Body' => ['Text' => ['Charset' => 'UTF-8', 'Data' => $body]],
 *       'Subject' => ['Charset' => 'UTF-8', 'Data' => $subject],
 *     ],
 *     'Source' => $from,
 *     'ReplyToAddresses' => [$u_email],
 *   ]);
 *   echo json_encode(['success' => true, 'message' => 'Your appointment request has been sent successfully.']);
 * } catch (AwsException $e) {
 *   echo json_encode(['success' => false, 'message' => 'Failed to send email.', 'error' => $e->getAwsErrorMessage()]);
 * }
 * exit;
 */

// Minimal success response (no email) — use this to verify the path works.
echo json_encode([
  'success' => true,
  'message' => 'Your appointment request has been received. We will get back to you shortly.'
]);
