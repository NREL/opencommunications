<?php

// Set the active configuration directory.
$settings['config_sync_directory'] = '../drupal_config/config';

$settings['hash_salt'] = 'cdXLPEnUGtZcgXEqwQAjCJ1T2Hp9jZs2TO1k6EASU6qWUS5KxRrWwP7rpprJkRIE6vW1FOuqCA';

$settings['update_free_access'] = FALSE;

if (isset($_SERVER['WWW_NREL'])) {
  // Never allow updating modules through UI.
  $settings['allow_authorize_operations'] = FALSE;

  if (PHP_SAPI !== 'cli') {
    // Fix HTTPS if we're behind load balancer.
    if ($_SERVER['HTTPS'] !== 'on' && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
      $_SERVER['HTTPS'] = 'on';
    }
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
      ini_set('session.cookie_secure', 1);
      ini_set('session.cookie_httponly', 1);
    }
  }
  $config['system.file']['path']['temporary'] = '/var/www/common/tmp';
  $config['system.mail']['interface']['default'] = 'ses_mail';

  /**
   * Fast 404 pages:
   */
  $config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
  $config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
  $config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

  /**
   * Container YAMLs
   */
  $settings['container_yamls'] = array (
    DRUPAL_ROOT . '/sites/default/services.yml',
    DRUPAL_ROOT . '/sites/default/local.services.yml',
    DRUPAL_ROOT . '/sites/default/ses_mailer.services.yml',
    DRUPAL_ROOT . '/modules/contrib/memcache/memcache.services.yml'
  );

  $env = strtoupper($_SERVER['WWW_NREL']);
  switch($env) {
    case 'DRUPALVM':
      $settings['trusted_host_patterns'] = array(
        $_SERVER['SERVER_NAME'],
      );
      $config['config_split.config_split.development']['status'] = FALSE;
      $config['config_split.config_split.drupalvm']['status'] = TRUE;
      break;

    case 'SANDBOX':

      break;

    case 'STAGE':

      break;

    case 'PROD':

      break;

  }
}

$settings['file_chmod_directory'] = 0775;
$settings['file_chmod_file'] = 0664;

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

$settings['file_public_path'] = 'sites/default/files';
$settings['file_private_path'] = 'sites/default/files/private';

if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

if (FALSE !== getenv('ENVIRONMENT')) {
  $env = getenv('ENVIRONMENT');

  $settings['ENVIRONMENT'] = $env;
  $databases['default']['default'] = [
    'database' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host' => getenv('DB_HOST'),
    'prefix' => '',
    'port' => '',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
  ];

  if ($env == 'STAGING' || $env == 'PRODUCTION') {
    if (!empty($_SERVER['REMOTE_ADDR'])) {
      $settings['reverse_proxy'] = TRUE;
      $settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];
    }
  }

  $config['system.mail']['interface']['default'] = 'ses_mail';
  if ($env == 'DOCKSAL') {
    $config['system.mail']['interface']['default'] = 'SMTPMailSystem';
    $config['smtp.settings']['smtp_on'] = 'on';
    $config['smtp.settings']['smtp_host'] = 'mail';
    $config['smtp.settings']['smtp_port'] = 1025;
    $config['smtp.settings']['smtp_protocol'] = 'standard';

    $config['system.performance']['css']['preprocess'] = 0;
    $config['system.performance']['js']['preprocess'] = 0;
    $config['system.logging']['error_level'] = 'verbose';

    $config['advagg.settings']['enabled'] = FALSE;

    $settings['trusted_host_patterns'] = array(
    );

    $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
  }

  if ($env == 'STAGING') {
    $config['system.mail']['interface']['default'] = 'SMTPMailSystem';
    $config['smtp.settings']['smtp_on'] = 'on';
    $config['smtp.settings']['smtp_host'] = 'localhost';
    $config['smtp.settings']['smtp_port'] = 1025;
    $config['smtp.settings']['smtp_protocol'] = 'standard';
    $settings['trusted_host_patterns'] = array(
    );
  }

  if ($env == 'PRODUCTION') {
    $config['system.mail']['interface']['default'] = 'SMTPMailSystem';
    $config['smtp.settings']['smtp_on'] = 'on';
    $config['smtp.settings']['smtp_host'] = getenv('SMTP_HOST');
    $config['smtp.settings']['smtp_port'] = getenv('SMTP_PORT');
    $config['smtp.settings']['smtp_username'] = getenv('SMTP_USER');
    $config['smtp.settings']['smtp_password'] = getenv('SMTP_PASSWORD');
    $settings['trusted_host_patterns'] = array(
    );
  }
  if (getenv('ENVIRONMENT') != 'PRODUCTION') {
    $config['config_split.config_split.development']['status'] = TRUE;
  } else {
    $config['config_split.config_split.development']['status'] = FALSE;
  }
  // Set the memcache server service
  if ($env == 'DOCKSAL') {
    $settings['memcache']['servers'] = ['memcached:11211' => 'default'];
  } elseif ($env == 'STAGING' || $env == 'PRODUCTION') {
    $settings['memcache']['servers'] = ['localhost:11211' => 'default'];
  }
}

// Automatically generated include for settings managed by ddev.
$ddev_settings = dirname(__FILE__) . '/settings.ddev.php';
if (is_readable($ddev_settings) && getenv('IS_DDEV_PROJECT') == 'true') {
  require $ddev_settings;
}
