<?php
//Begin Really Simple Security key
define('RSSSL_KEY', 'PhrTGWcrLqHOW0crSWmpy7ZG6Z1Tw4sGHJZpdpxJKsrWMZhBoho2YyQZjJGK6gKN');
//END Really Simple Security key
/**
 * wp-config.php para blockopsa.com
 * - Fuerza HTTPS y URLs correctas
 * - Modo DIAGNÓSTICO para aislar problemas de caché/minificación
 * - Redis desactivado en diagnóstico
 * - Multisite desactivado (actívalo solo si lo usas)
 */

/* ============================================================
 * 0) AJUSTE RÁPIDO: MODO DIAGNÓSTICO (true = pruebas, false = producción)
 * ============================================================ */
define('WP_DIAGNOSTICO', false);

/* ============================================================
 * 1) DATOS DE BASE DE DATOS (YA RELLENADOS)
 * ============================================================ */
define('DB_NAME',     'ceumkcjrju');
define('DB_USER',     'ceumkcjrju');
define('DB_PASSWORD', 'AwJ5SC3zUs');
define('DB_HOST',     'localhost');
define('DB_CHARSET',  'utf8mb4');
define('DB_COLLATE',  '');

/* ============================================================
 * 2) URLS DEL SITIO (FORZAMOS HTTPS)
 * ============================================================ */
define('WP_HOME',    'https://blockopsa.com');
define('WP_SITEURL', 'https://blockopsa.com');
define('FORCE_SSL_ADMIN', true);

/* ============================================================
 * 3) KEYS & SALTS
 * Opción A) Mantén tu wp-salt.php
 * Opción B) Pega aquí las constantes de https://api.wordpress.org/secret-key/1.1/salt/
 * ============================================================ */
if ( file_exists(__DIR__ . '/wp-salt.php') ) {
  require __DIR__ . '/wp-salt.php';
} else {
  // ⚠️ Genera y pega tus salts aquí si no usas wp-salt.php
  // define('AUTH_KEY',         '...');
  // define('SECURE_AUTH_KEY',  '...');
  // define('LOGGED_IN_KEY',    '...');
  // define('NONCE_KEY',        '...');
  // define('AUTH_SALT',        '...');
  // define('SECURE_AUTH_SALT', '...');
  // define('LOGGED_IN_SALT',   '...');
  // define('NONCE_SALT',       '...');
}

/* ============================================================
 * 4) PREFIJO DE TABLAS (mantengo el que tenías)
 * ============================================================ */
$table_prefix = '9GjElhQs_';

/* ============================================================
 * 5) MÉTODO DE FS Y PERMISOS
 * ============================================================ */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR', (0775 & ~ umask()));
define('FS_CHMOD_FILE', (0664 & ~ umask()));

/* ============================================================
 * 6) COOKIES DE SESIÓN
 * En diagnóstico, relajamos "secure" para descartar problemas.
 * ============================================================ */
if (WP_DIAGNOSTICO) {
  @ini_set('session.cookie_httponly', true);
  @ini_set('session.cookie_secure', false);   // ← diagnóstico
  @ini_set('session.use_only_cookies', true);
} else {
  @ini_set('session.cookie_httponly', true);
  @ini_set('session.cookie_secure', true);    // ← producción
  @ini_set('session.use_only_cookies', true);
}

/* ============================================================
 * 7) CACHÉ / MINIFICACIÓN / DEBUG
 * ============================================================ */
if (WP_DIAGNOSTICO) {
  define('WP_CACHE', false);
  define('SCRIPT_DEBUG', true);
} else {
  define('WP_CACHE', true);
  define('SCRIPT_DEBUG', false);
}

/* ============================================================
 * 8) REDIS OBJECT CACHE (desactivado en diagnóstico)
 * Config estable/sencilla para producción si la usas.
 * ============================================================ */
if (WP_DIAGNOSTICO) {
  define('WP_REDIS_DISABLED', false);
} else {
  define('WP_REDIS_DISABLED', false);
  define('WP_REDIS_CONFIG', [
    'host'          => '127.0.0.1',
    'port'          => 6379,
    'database'      => 0,
    // 'password'    => 'TU_PASSWORD_SI_APLICA',
    'serializer'    => 'php',
    'compression'   => false,
    'prefetch'      => false,
    'save_commands' => false,
    'debug'         => false,
    'prefix'        => DB_NAME . ':',
  ]);
}

/* ============================================================
 * 9) MULTISITE (desactivado salvo que lo uses)
 * ============================================================ */
define('WP_ALLOW_MULTISITE', false);

/* ============================================================
 * 10) MODO DEBUG GENERAL
 * ============================================================ */
define('WP_DEBUG', WP_DIAGNOSTICO ? true : false);

/* ============================================================
 * 11) RUTA ABSOLUTA Y CARGA DE WP
 * ============================================================ */
if ( ! defined('ABSPATH') ) {
  define('ABSPATH', __DIR__ . '/');
}
require_once ABSPATH . 'wp-settings.php';
