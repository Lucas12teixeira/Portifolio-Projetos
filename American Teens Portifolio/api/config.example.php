<?php
/**
 * American Teens - Configuration File Example
 * 
 * Copy this file to config.php and update with your settings
 * 
 * IMPORTANT: Never commit config.php with real credentials!
 * The config.php file is in .gitignore for security.
 */

// ============================================
// DATABASE CONFIGURATION
// ============================================

define('DB_HOST', 'localhost');           // Database host (usually localhost)
define('DB_USER', 'your_database_user');  // Database username
define('DB_PASS', 'your_database_pass');  // Database password
define('DB_NAME', 'americateens');        // Database name
define('DB_CHARSET', 'utf8mb4');          // Character set (recommended: utf8mb4)
define('DB_PORT', 3306);                  // MySQL port (default: 3306)

// ============================================
// JWT CONFIGURATION
// ============================================

// IMPORTANT: Generate a secure random key!
// Use: php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
define('JWT_SECRET', 'CHANGE-THIS-TO-A-SECURE-RANDOM-256-BIT-KEY');

// Token expiration times (in seconds)
define('JWT_EXPIRATION', 3600);           // Access token: 1 hour
define('REFRESH_TOKEN_EXPIRATION', 604800); // Refresh token: 7 days

// JWT Algorithm
define('JWT_ALGORITHM', 'HS256');         // Options: HS256, HS384, HS512

// ============================================
// APPLICATION CONFIGURATION
// ============================================

// Application URL (no trailing slash)
define('APP_URL', 'http://localhost:8000');

// Application environment: 'development' or 'production'
define('APP_ENV', 'development');

// Application name
define('APP_NAME', 'American Teens');

// Application version
define('APP_VERSION', '1.0.0');

// Timezone
define('APP_TIMEZONE', 'America/Sao_Paulo');

// ============================================
// SECURITY CONFIGURATION
// ============================================

// CORS (Cross-Origin Resource Sharing)
define('ENABLE_CORS', true);

// Allowed origins for CORS (array of domains)
define('ALLOWED_ORIGINS', [
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    // Add your production domain:
    // 'https://yourdomain.com',
]);

// Session configuration
define('SESSION_LIFETIME', 86400);        // 24 hours in seconds
define('SESSION_COOKIE_NAME', 'americateens_session');
define('SESSION_SECURE', false);          // Set to true in production with HTTPS
define('SESSION_HTTP_ONLY', true);        // Prevent JavaScript access to session cookie
define('SESSION_SAME_SITE', 'Lax');       // Options: Lax, Strict, None

// Password hashing
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 12);         // Higher = more secure but slower

// Rate limiting (requests per minute)
define('RATE_LIMIT_ENABLED', false);      // Enable in production
define('RATE_LIMIT_MAX_REQUESTS', 60);    // Max requests per minute
define('RATE_LIMIT_WINDOW', 60);          // Time window in seconds

// ============================================
// FILE UPLOAD CONFIGURATION
// ============================================

// Maximum file size (in bytes)
define('UPLOAD_MAX_SIZE', 5242880);       // 5 MB

// Upload directories (relative to project root)
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('AVATAR_DIR', UPLOAD_DIR . 'avatars/');
define('EVENT_DIR', UPLOAD_DIR . 'events/');
define('POST_DIR', UPLOAD_DIR . 'posts/');

// Allowed image MIME types
define('ALLOWED_IMAGE_TYPES', [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp'
]);

// Allowed file extensions
define('ALLOWED_EXTENSIONS', [
    'jpg', 'jpeg', 'png', 'gif', 'webp'
]);

// ============================================
// EMAIL CONFIGURATION (Optional)
// ============================================

define('EMAIL_ENABLED', false);           // Enable email features
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_ENCRYPTION', 'tls');         // Options: tls, ssl
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password'); // Gmail App Password, not regular password
define('SMTP_FROM', 'noreply@americateens.com');
define('SMTP_FROM_NAME', 'American Teens');

// ============================================
// DEBUG & LOGGING
// ============================================

// Debug mode (ALWAYS FALSE IN PRODUCTION!)
define('DEBUG_MODE', true);

// Display errors (ALWAYS FALSE IN PRODUCTION!)
define('DISPLAY_ERRORS', true);

// Error logging
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');

// Query logging (for development)
define('LOG_QUERIES', false);
define('QUERY_LOG_FILE', __DIR__ . '/logs/query.log');

// API request logging
define('LOG_REQUESTS', false);
define('REQUEST_LOG_FILE', __DIR__ . '/logs/request.log');

// ============================================
// CACHE CONFIGURATION
// ============================================

define('CACHE_ENABLED', true);
define('CACHE_DRIVER', 'file');           // Options: file, redis, memcached
define('CACHE_LIFETIME', 3600);           // 1 hour in seconds
define('CACHE_DIR', __DIR__ . '/cache/');

// ============================================
// API CONFIGURATION
// ============================================

// API versioning
define('API_VERSION', 'v1');

// API rate limiting per endpoint (optional)
define('API_RATE_LIMITS', [
    'auth/login' => 5,                    // 5 requests per minute
    'auth/register' => 3,                 // 3 requests per minute
    'chat/send' => 30,                    // 30 requests per minute
]);

// API pagination
define('API_DEFAULT_PAGE_SIZE', 20);
define('API_MAX_PAGE_SIZE', 100);

// ============================================
// FEATURE FLAGS
// ============================================

// Enable/disable features
define('FEATURE_CHAT', true);
define('FEATURE_EVENTS', true);
define('FEATURE_QUIZ', true);
define('FEATURE_BIBLE', true);
define('FEATURE_WALL', true);
define('FEATURE_NOTIFICATIONS', false);   // Coming soon
define('FEATURE_VIDEO_CALL', false);      // Coming soon

// ============================================
// EXTERNAL SERVICES (Optional)
// ============================================

// Analytics (Google Analytics, etc.)
define('ANALYTICS_ENABLED', false);
define('ANALYTICS_ID', 'UA-XXXXXXXXX-X');

// Error tracking (Sentry, Bugsnag, etc.)
define('ERROR_TRACKING_ENABLED', false);
define('ERROR_TRACKING_DSN', '');

// ============================================
// MAINTENANCE MODE
// ============================================

define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'We are currently performing maintenance. Please check back soon.');
define('MAINTENANCE_ALLOWED_IPS', [
    '127.0.0.1',                          // Localhost
    // Add your IP to access during maintenance
]);

// ============================================
// APPLY CONFIGURATION
// ============================================

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Set error reporting based on environment
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', DISPLAY_ERRORS ? 1 : 0);
    ini_set('log_errors', LOG_ERRORS ? 1 : 0);
}

// Set error log file
if (LOG_ERRORS) {
    ini_set('error_log', ERROR_LOG_FILE);
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get configuration value
 * @param string $key Configuration key
 * @param mixed $default Default value if not found
 * @return mixed
 */
function config($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

/**
 * Check if we're in production
 * @return bool
 */
function is_production() {
    return APP_ENV === 'production';
}

/**
 * Check if we're in development
 * @return bool
 */
function is_development() {
    return APP_ENV === 'development';
}

/**
 * Check if debug mode is enabled
 * @return bool
 */
function is_debug() {
    return DEBUG_MODE === true;
}

// ============================================
// ENVIRONMENT-SPECIFIC SETTINGS
// ============================================

if (APP_ENV === 'production') {
    // Production-specific settings
    
    // Force HTTPS
    define('FORCE_HTTPS', true);
    
    // Security headers
    define('SECURITY_HEADERS', [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
    ]);
    
} else {
    // Development-specific settings
    
    define('FORCE_HTTPS', false);
    define('SECURITY_HEADERS', []);
}

// ============================================
// VALIDATION
// ============================================

// Validate required settings
$required_settings = [
    'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME',
    'JWT_SECRET', 'APP_URL'
];

foreach ($required_settings as $setting) {
    if (!defined($setting) || empty(constant($setting))) {
        die("ERROR: Required configuration '$setting' is not set in config.php");
    }
}

// Warn about default JWT secret
if (JWT_SECRET === 'CHANGE-THIS-TO-A-SECURE-RANDOM-256-BIT-KEY') {
    if (APP_ENV === 'production') {
        die('ERROR: You must change the JWT_SECRET in production!');
    } else {
        trigger_error('WARNING: Using default JWT_SECRET. Change it before deploying to production!', E_USER_WARNING);
    }
}

// ============================================
// END OF CONFIGURATION
// ============================================

// Configuration loaded successfully
define('CONFIG_LOADED', true);
