<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAlULPQJo:APA91bGklmYd6tT20xoh5h7KHDUdJ5V8_xHvKpS385Tw4RtDQPszWUak5n4_02gB3fyhgh1LNJwALUDc29colsC5yTo7FDbc7ukhgFwVtCop1y4coZV7f_sqrc5_Ske_9HM9RbjV8s29',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];