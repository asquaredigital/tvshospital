<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require __DIR__ . '/../vendor/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$config = require __DIR__ . '/../vendor/config.php';

$awsKey    = $config['aws']['key']   ?? '';
$awsSecret = $config['aws']['secret']?? '';
$awsRegion = $config['aws']['region']?? '';

$sesClient = new SesClient([
    'version'     => 'latest',
    'region'      => $awsRegion,
    'credentials' => [
        'key'    => $awsKey,
        'secret' => $awsSecret,
    ],
]);

// Read expected fields from the form (match your HTML/JS)
$u_name  = trim($_POST['u_name']  ?? '');
$u_email = trim($_POST['u_email'] ?? '');
$phone   = trim($_POST['phone']   ?? '');
$doctor  = trim($_POST['doctor']  ?? '');
$msg     = trim($_POST['message'] ?? '');

// Basic validation
if ($u_name === '' || $u_email === '' || $doctor === '') {
    echo json_encode(['success' => false, 'message' => 'Please provide your name, email, and select a doctor.']);
    exit;
}
if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Email content
$subject = 'New Appointment Request - ' . ($doctor !== '' ? $doctor : 'Doctor not specified');
$body = "New appointment request from the website:\n\n"
      . "Name: {$u_name}\n"
      . "Email: {$u_email}\n"
      . "Phone: " . ($phone !== '' ? $phone : 'N/A') . "\n"
      . "Doctor: " . ($doctor !== '' ? $doctor : 'N/A') . "\n"
      . "Message:\n{$msg}\n";

// IMPORTANT: These must be verified in SES (or you must be out of sandbox)
$senderEmail    = 'asquaremailer@gmail.com';     // FROM (verified)
$recipientEmail = 'suganthmaddy35@gmail.com';   // TO   (verified if SES sandbox)

try {
    $result = $sesClient->sendEmail([
        'Destination' => [
            'ToAddresses' => [$recipientEmail],
        ],
        'Message' => [
            'Body' => [
                'Text' => [
                    'Charset' => 'UTF-8',
                    'Data'    => $body,
                ],
            ],
            'Subject' => [
                'Charset' => 'UTF-8',
                'Data'    => $subject,
            ],
        ],
        'Source'           => $senderEmail,
        'ReplyToAddresses' => [$u_email], // Reply to the user
    ]);

    echo json_encode([
        'success'   => true,
        'message'   => 'Your appointment request has been sent successfully.',
        'messageId' => $result->get('MessageId') ?? null
    ]);
} catch (AwsException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send email.',
        'error'   => $e->getAwsErrorMessage()
    ]);
}
