<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require __DIR__ . '../vendor/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$config = require __DIR__ . '../vendor/config.php';

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

// Read form fields
$u_name = $_POST['name'];
$u_email = $_POST['email'];
$phone = $_POST['phone'];
$msg = $_POST['message'];

// Validation
if ($u_name === '' || $u_email === '') {
    echo json_encode(['success' => false, 'message' => 'Please provide your name and email.']);
    exit;
}
if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Email content
$subject = 'New Appointment Request';
$body = "New appointment request from the website:\n\n"
      . "Name: {$u_name}\n"
      . "Email: {$u_email}\n"
      . "Phone: " . ($phone !== '' ? $phone : 'N/A') . "\n"
      . "Message:\n{$msg}\n";

// Verified SES emails
$senderEmail    = 'asquaremailer@gmail.com';
$recipientEmail = 'elavarasan5193@gmail.com';

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
        'ReplyToAddresses' => [$u_email],
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
