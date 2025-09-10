<?php
return [
  'is_production'      => env('MIDTRANS_IS_PRODUCTION', false),
  'server_key'         => env('MIDTRANS_SERVER_KEY'),
  'client_key'         => env('MIDTRANS_CLIENT_KEY'),
  'merchant_id'        => env('MIDTRANS_MERCHANT_ID'),
  'append_notif_url'   => env('MIDTRANS_APPEND_NOTIF_URL', null),
  'override_notif_url' => env('MIDTRANS_OVERRIDE_NOTIF_URL', null),
];
