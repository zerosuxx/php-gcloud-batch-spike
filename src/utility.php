<?php

use Google\Cloud\PubSub\PubSubClient;

/**
 * Publishes a message for a Pub/Sub topic.
 *
 * The publisher should be used in conjunction with the `google-cloud-batch`
 * daemon, which should be running in the background.
 *
 * To start the daemon, from your project root call `vendor/bin/google-cloud-batch daemon`.
 *
 * @param string $projectId The Google project ID.
 * @param string $topicName The Pub/Sub topic name.
 * @param string $message The message to publish.
 */
function publish_message_batch($projectId, $topicName, $message)
{
// Check if the batch daemon is running.
    if (getenv('IS_BATCH_DAEMON_RUNNING') !== 'true') {
        trigger_error(
            'The batch daemon is not running. Call ' .
            '`vendor/bin/google-cloud-batch daemon` from ' .
            'your project root to start the daemon.',
            E_USER_NOTICE
        );
    }

    $batchOptions = [
        'batchSize' => 100, // Max messages for each batch.
        'callPeriod' => 0.01, // Max time in seconds between each batch publish.
    ];

    $pubsub = new PubSubClient([
        'projectId' => $projectId,
    ]);
    $topic = $pubsub->topic($topicName);
    $publisher = $topic->batchPublisher([
        'batchOptions' => $batchOptions
    ]);

    for ($i = 0; $i < 10; $i++) {
        $publisher->publish(['data' => $message]);
    }

    print('Messages enqueued for publication.' . PHP_EOL);
}