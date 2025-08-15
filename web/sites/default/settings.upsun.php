<?php


if (getenv('PLATFORM_PROJECT') != "") {
<?php
if (!empty($_ENV['PLATFORM_PROJECT'])) {
  $rels = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS'] ?? ''), true) ?: [];
  if (!empty($rels['db'][0])) {
    $db = $rels['db'][0];

    // Some sanity defaults.
    $driver = 'mysql'; // Drupal wants 'mysql' even when the service is MariaDB.
    $host   = $db['host'] ?? 'database.internal';
    $port   = (int)($db['port'] ?? 3306);

    $databases['default']['default'] = [
      'database'  => $db['path'] ?? 'drupal',
      'username'  => $db['username'] ?? 'user',
      'password'  => $db['password'] ?? '',
      'host'      => $host,
      'port'      => $port,
      'driver'    => $driver,
      'prefix'    => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    ];
  }

  // Files/config (kept from your version).
  $settings['hash_salt'] = 'bf95508a645408b33848673dba1368d4f976bdb0ee4cd4d5b97dc9ddf9b88211';
  $settings['config_sync_directory'] = $settings['config_sync_directory'] ?? 'sites/default/files/sync';

  // Loosen for testing; tighten later
  $settings['trusted_host_patterns'] = ['.*'];

  // Helpful while debugging:
  $config['system.logging']['error_level'] = 'verbose';
  $settings['rebuild_access'] = TRUE;

}
