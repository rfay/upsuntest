<?php
if (!empty($_ENV['PLATFORM_PROJECT'])) {
  $rels = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS'] ?? ''), true) ?: [];

  if (!empty($rels['mariadb1'][0])) {
    $db = $rels['mariadb1'][0]; // <-- relationship name matches your config

    $databases['default']['default'] = [
      'database'  => $db['path'] ?? 'drupal',
      'username'  => $db['username'] ?? 'user',
      'password'  => $db['password'] ?? '',
      'host'      => $db['host'] ?? 'localhost',
      'port'      => (int) ($db['port'] ?? 3306),
      'driver'    => 'mysql', // Drupal expects 'mysql' even for MariaDB
      'prefix'    => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    ];
  }

  // Your existing bits:
  $settings['hash_salt'] = $settings['hash_salt'] ?? 'bf95508a645408b33848673dba1368d4f976bdb0ee4cd4d5b97dc9ddf9b88211';
  $settings['config_sync_directory'] = $settings['config_sync_directory'] ?? 'sites/default/files/sync';
  $settings['trusted_host_patterns'] = $settings['trusted_host_patterns'] ?? ['.*'];
  $config['system.logging']['error_level'] = 'verbose';
  $settings['rebuild_access'] = TRUE;
}
