<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

// ---- Adjust these paths to your server layout ----
require __DIR__ . '/../vendor/vendor/autoload.php';  // Composer autoload
$config = require __DIR__ . '/../vendor/config.php'; // returns ['aws' => ['key'=>..., 'secret'=>..., 'region'=>...]]

// Reject non-POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Read config
$awsKey    = $config['aws']['key']    ?? '';
$awsSecret = $config['aws']['secret'] ?? '';
$awsRegion = $config['aws']['region'] ?? '';

// Create SES client
$sesClient = new SesClient([
    'version'     => 'latest',
    'region'      => $awsRegion,
    'credentials' => [
        'key'    => $awsKey,
        'secret' => $awsSecret,
    ],
]);

// Read form fields (must match front-end)
$u_name  = trim($_POST['u_name']  ?? '');
$u_email = trim($_POST['u_email'] ?? '');
$phone   = trim($_POST['phone']   ?? '');
$doctor  = trim($_POST['doctor']  ?? '');
$msg     = trim($_POST['message'] ?? '');

// Validate
if ($u_name === '' || $u_email === '' || $doctor === '') {
    echo json_encode(['success' => false, 'message' => 'Please provide your name, email, and select a doctor.']);
    exit;
}
if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Compose email
$subject = 'New Appointment Request - ' . ($doctor !== '' ? $doctor : 'Doctor not specified');
$body = "New appointment request from the website:\n\n"
      . "Name: {$u_name}\n"
      . "Email: {$u_email}\n"
      . "Phone: " . ($phone !== '' ? $phone : 'N/A') . "\n"
      . "Doctor: " . ($doctor !== '' ? $doctor : 'N/A') . "\n"
      . "Message:\n{$msg}\n";

// ---- Replace with your verified identities in SES ----
$senderEmail    = 'asquaremailer@gmail.com';     // FROM (must be verified in SES, or domain identity)
$recipientEmail = 'elavarasan5193@gmail.com';   // TO (must be verified if SES account is in sandbox)

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
        'ReplyToAddresses' => [$u_email], // replies go to the requester
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
