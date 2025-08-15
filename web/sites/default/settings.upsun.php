<?php
// Only apply on Upsun.
if (!empty($_ENV['PLATFORM_PROJECT'])) {

  // Decode relationships (base64 JSON) and grab the app's primary DB.
  $relationships_raw = $_ENV['PLATFORM_RELATIONSHIPS'] ?? '';
  $relationships = $relationships_raw ? json_decode(base64_decode($relationships_raw), true) : [];

  if (!empty($relationships['db'][0])) {
    $db = $relationships['db'][0];

    // Drupal expects 'mysql' driver for MySQL/MariaDB.
    $databases['default']['default'] = [
      'database'  => $db['path'] ?? 'drupal',
      'username'  => $db['username'] ?? 'user',
      'password'  => $db['password'] ?? '',
      'host'      => $db['host'] ?? 'localhost',
      'port'      => (int)($db['port'] ?? 3306),
      'driver'    => 'mysql',
      'prefix'    => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    ];
  }

  // Files / config sync
  $settings['hash_salt'] = $settings['hash_salt'] ?? 'bf95508a645408b33848673dba1368d4f976bdb0ee4cd4d5b97dc9ddf9b88211';
  $settings['config_sync_directory'] = $settings['config_sync_directory'] ?? 'sites/default/files/sync';

  // While testing, allow broad hostnames; tighten when stable.
  $settings['trusted_host_patterns'] = $settings['trusted_host_patterns'] ?? ['.*'];

  // Helpful for diagnosing the 500s; remove later.
  $config['system.logging']['error_level'] = 'verbose';
  $settings['rebuild_access'] = TRUE;
}
