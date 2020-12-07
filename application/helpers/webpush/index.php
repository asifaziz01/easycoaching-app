<?php
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

include (dirname(__FILE__) . "/vendor/autoload.php");

/*
{
  "endpoint": "https://fcm.googleapis.com/fcm/send/ew_Zd-qU85E:APA91bEDFwVRwqK6_VbUCq3i9_oQDAnOpMjmozdCpOvwm6Bvo2MZIRrrX06CeUvPLhH305AmhDQ_ZjZc3HAY2b6dkIwBj8G-HOn6ekPyAOXbVUBaJMbVpWuU6Sa5PoiWcnGwfZRMoA-p",
  "expirationTime": null,
  "keys": {
    "p256dh": "BFjltqyenZlDtBjdO4pPtXLVCFTvGRwJUXYeN12mNTUCNP_S2iQ45wWV0ceTyT-YahEWScrhLFPwKPDMenYWogk",
    "auth": "yujRURMNlxNAy5noOuG9EQ"
  }
}
*/

// array of notifications
$notifications = [
	[
		'subscription' => Subscription::create([
			"endpoint" => "https://fcm.googleapis.com/fcm/send/ew_Zd-qU85E:APA91bEDFwVRwqK6_VbUCq3i9_oQDAnOpMjmozdCpOvwm6Bvo2MZIRrrX06CeUvPLhH305AmhDQ_ZjZc3HAY2b6dkIwBj8G-HOn6ekPyAOXbVUBaJMbVpWuU6Sa5PoiWcnGwfZRMoA-p",
			"expirationTime"=> null,
			"keys" => [
				'p256dh' => 'BFjltqyenZlDtBjdO4pPtXLVCFTvGRwJUXYeN12mNTUCNP_S2iQ45wWV0ceTyT-YahEWScrhLFPwKPDMenYWogk',
				'auth' => 'yujRURMNlxNAy5noOuG9EQ'
			],
		]),
		'payload' => '{"msg":"Hello World!"}',
    ],
];

$webPush = new WebPush([
    'VAPID' => [
        'subject' => 'https://developers.google.com/web/fundamentals/',
        'publicKey' => 'BBpQAy6d2Q1LKgwAqLU96oHM1Ugyvyq8eDiPlyptO40juyjFQV9wgC6Sem2VcjmuFKY081z30DwLYpz3kF9YA8A',
        'privateKey' => 'aX7QYnqdpMgCAUX2c4mGzRYSjNpZ2mzWiF5iQDZe-3g'
    ],
]);

// send multiple notifications with payload
foreach ($notifications as $notification) {
    $webPush->sendNotification(
        $notification['subscription'],
        $notification['payload'] // optional (defaults null)
    );
}

/**
 * Check sent results
 * @var MessageSentReport $report
 */
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    }
}