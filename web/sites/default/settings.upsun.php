<?php
if (!empty($_ENV['PLATFORM_PROJECT'])) {
  $rels = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS'] ?? ''), true) ?: [];

  // Choose the correct relationship: prefer 'db', then 'mariadb1', else auto-detect MySQL/MariaDB.
  $conn = null;
  if (!empty($rels['db'][0])) {
    $conn = $rels['db'][0];
  } elseif (!empty($rels['mariadb1'][0])) {
    $conn = $rels['mariadb1'][0];
  } else {
    foreach ($rels as $relName => $endpoints) {
      if (!is_array($endpoints)) { continue; }
      foreach ($endpoints as $ep) {
        $scheme = $ep['scheme'] ?? $ep['type'] ?? '';
        if (in_array($scheme, ['mysql', 'mariadb'], true)) { $conn = $ep; break 2; }
      }
    }
  }

  if ($conn) {
    $databases['default']['default'] = [
      'database'  => $conn['path'] ?? 'drupal',
      'username'  => $conn['username'] ?? 'user',
      'password'  => $conn['password'] ?? '',
      'host'      => $conn['host'] ?? 'localhost',
      'port'      => (int) ($conn['port'] ?? 3306),
      'driver'    => 'mysql', // Drupal expects 'mysql' even for MariaDB
      'prefix'    => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    ];
  } else {
    error_log('[Upsun] No MySQL/MariaDB relationship found in PLATFORM_RELATIONSHIPS');
  }

  // Your existing bits:
  $settings['hash_salt'] = $settings['hash_salt'] ?? 'bf95508a645408b33848673dba1368d4f976bdb0ee4cd4d5b97dc9ddf9b88211';
  $settings['config_sync_directory'] = $settings['config_sync_directory'] ?? 'sites/default/files/sync';
  $settings['trusted_host_patterns'] = $settings['trusted_host_patterns'] ?? ['.*'];
  $config['system.logging']['error_level'] = 'verbose';
  $settings['rebuild_access'] = TRUE;
}
