<?php

// IMMEDIATE INSTALL REDIRECT - Place this at the very top
if (isset($_GET['install']) || strpos($_SERVER['REQUEST_URI'], '/install') !== false) {
    $requestUri = $_SERVER['REQUEST_URI'];

    // Handle different install file requests
    if (strpos($requestUri, '/install/step2.php') !== false) {
        $installFile = __DIR__ . '/install/step2.php';
    } elseif (strpos($requestUri, '/install/step3.php') !== false) {
        $installFile = __DIR__ . '/install/step3.php';
    } elseif (strpos($requestUri, '/install/delete.php') !== false) {
        $installFile = __DIR__ . '/install/delete.php';
    } else {
        $installFile = __DIR__ . '/install/index.php';
    }

    if (file_exists($installFile)) {
        // Set proper headers for direct file serving
        header('Content-Type: text/html; charset=UTF-8');
        // Include the install file directly
        include $installFile;
        exit();
    }
}

// Prevent infinite install redirect loops
if (strpos($_SERVER['REQUEST_URI'], '/install/') !== false && substr_count($_SERVER['REQUEST_URI'], '/install/') > 1) {
    header("Location: /");
    exit();
}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Installation Check and Redirect
|--------------------------------------------------------------------------
|
| Check if the application needs to be installed. If installation is
| required, redirect to the installation script. This ensures that
| the installation process is completed before accessing the app.
|
*/

// Check if app is installed by looking for marker files
$isInstalled = file_exists(__DIR__ . '/../storage/app/installed.txt') ||
               file_exists(__DIR__ . '/../.env.example.backup');

// Check if install directory exists
$installExists = is_dir(__DIR__ . '/install');

// Get current path to avoid redirect loops
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$isInInstallPath = strpos($currentPath, '/install/') !== false;

// Installation logic
if (!$isInstalled && $installExists && !$isInInstallPath) {
    // Show installation welcome page directly
    showInstallationWelcome();
    exit();
} elseif (!$isInstalled && !$installExists) {
    // No install directory found
    showInstallationError();
    exit();
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

/*
|--------------------------------------------------------------------------
| Installation Welcome Page
|--------------------------------------------------------------------------
|
| This function displays the initial installation welcome page that
| guides users through the installation process.
|
*/
function showInstallationWelcome() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PivotPro HRMS - Installation Required</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .install-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .install-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
                max-width: 600px;
                width: 90%;
            }
            .install-header {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                padding: 2rem;
                text-align: center;
            }
            .install-body {
                padding: 2rem;
            }
            .feature-item {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
            }
            .feature-icon {
                background: #667eea;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
                font-size: 18px;
            }
            .btn-install {
                background: linear-gradient(45deg, #667eea, #764ba2);
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                color: white;
                font-weight: 600;
                transition: transform 0.3s ease;
            }
            .btn-install:hover {
                transform: translateY(-2px);
                color: white;
            }
            .requirements-check {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 1.5rem;
                margin-bottom: 2rem;
            }
            .requirement-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #dee2e6;
            }
            .requirement-item:last-child {
                border-bottom: none;
            }
            .status-ok { color: #28a745; }
            .status-error { color: #dc3545; }
        </style>
    </head>
    <body>
        <div class="install-container">
            <div class="install-card">
                <div class="install-header">
                    <i class="fas fa-rocket fa-3x mb-3"></i>
                    <h1 class="mb-2">Welcome to PivotPro HRMS</h1>
                    <p class="mb-0">Let's get your Human Resource Management System up and running!</p>
                </div>



                <div class="install-body">
                    <!-- Features Overview -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="fas fa-star"></i> What's Included</h5>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <strong>Employee Management</strong>
                                <div class="text-muted small">Complete employee lifecycle management</div>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <strong>Attendance Tracking</strong>
                                <div class="text-muted small">Time tracking and attendance management</div>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <strong>Leave Management</strong>
                                <div class="text-muted small">Leave requests and approval workflows</div>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <strong>Payroll Processing</strong>
                                <div class="text-muted small">Automated payroll calculations and payslips</div>
                            </div>
                        </div>
                    </div>

                    <!-- System Requirements Check -->
                    <div class="requirements-check">
                        <h5 class="mb-3"><i class="fas fa-check-circle"></i> System Requirements</h5>

                        <div class="requirement-item">
                            <span>PHP Version (>= 8.0)</span>
                            <span class="<?php echo version_compare(PHP_VERSION, '8.0.0', '>=') ? 'status-ok' : 'status-error'; ?>">
                                <i class="fas fa-<?php echo version_compare(PHP_VERSION, '8.0.0', '>=') ? 'check' : 'times'; ?>"></i>
                                <?php echo PHP_VERSION; ?>
                            </span>
                        </div>

                        <div class="requirement-item">
                            <span>MySQL Extension</span>
                            <span class="<?php echo extension_loaded('pdo_mysql') ? 'status-ok' : 'status-error'; ?>">
                                <i class="fas fa-<?php echo extension_loaded('pdo_mysql') ? 'check' : 'times'; ?>"></i>
                                <?php echo extension_loaded('pdo_mysql') ? 'Available' : 'Not Available'; ?>
                            </span>
                        </div>

                        <div class="requirement-item">
                            <span>OpenSSL Extension</span>
                            <span class="<?php echo extension_loaded('openssl') ? 'status-ok' : 'status-error'; ?>">
                                <i class="fas fa-<?php echo extension_loaded('openssl') ? 'check' : 'times'; ?>"></i>
                                <?php echo extension_loaded('openssl') ? 'Available' : 'Not Available'; ?>
                            </span>
                        </div>

                        <div class="requirement-item">
                            <span>Storage Directory Writable</span>
                            <span class="<?php echo is_writable(__DIR__ . '/../storage') ? 'status-ok' : 'status-error'; ?>">
                                <i class="fas fa-<?php echo is_writable(__DIR__ . '/../storage') ? 'check' : 'times'; ?>"></i>
                                <?php echo is_writable(__DIR__ . '/../storage') ? 'Writable' : 'Not Writable'; ?>
                            </span>
                        </div>
                    </div>



                    <!-- Debug: Check install directory -->
                    <?php
                    // Debug: Check install directory
                    $installDir = __DIR__ . '/install';
                    $installIndex = $installDir . '/index.php';

                    echo '<div class="alert alert-info">';
                    echo '<strong>Debug Information:</strong><br>';
                    echo 'Install Directory Exists: ' . (is_dir($installDir) ? 'YES' : 'NO') . '<br>';
                    echo 'Install Index Exists: ' . (file_exists($installIndex) ? 'YES' : 'NO') . '<br>';
                    echo 'Install Directory Path: ' . htmlspecialchars($installDir) . '<br>';

                    if (is_dir($installDir)) {
                        $files = scandir($installDir);
                        echo 'Files in install directory: ' . implode(', ', $files) . '<br>';
                    }
                    echo '</div>';
                    ?>

                    <!-- Installation Notice -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle"></i>
                        <strong>Installation Notice:</strong> The installation process will set up your database,
                        create necessary tables, and configure your system settings. This typically takes 2-3 minutes.
                    </div>

                    <!-- Installation Buttons -->
                    <div class="text-center">
                        <a href="./install/index.php" class="btn btn-install btn-lg me-3">
                            <i class="fas fa-play"></i> Start Installation
                        </a>

                        <button class="btn btn-outline-secondary" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Check Again
                        </button>
                    </div>



                    <script>
                    function goToInstall() {
                        // Disable button to prevent multiple clicks
                        document.getElementById('installBtn').disabled = true;

                        // Try multiple methods to navigate to install
                        const installPaths = [
                            '/install/index.php',
                            './install/index.php',
                            'install/index.php',
                            window.location.pathname + 'install/index.php'
                        ];

                        // Try the first path
                        window.location.replace(window.location.origin + installPaths[0]);
                    }
                    </script>

                    <!-- Prerequisites -->
                    <div class="mt-4">
                        <h6 class="text-muted">Before you start, make sure you have:</h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-check text-success"></i> Database credentials (MySQL)</li>
                            <li><i class="fas fa-check text-success"></i> Purchase code (use 'pivotpro' for local installation)</li>
                            <li><i class="fas fa-check text-success"></i> Web server with PHP 8.0+ support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Auto-refresh requirements check every 30 seconds
            setTimeout(function() {
                const statusElements = document.querySelectorAll('.status-error');
                if (statusElements.length > 0) {
                    console.log('Some requirements not met. Checking again in 30 seconds...');
                    setTimeout(() => location.reload(), 30000);
                }
            }, 1000);
        </script>
    </body>
    </html>
    <?php
}

/*
|--------------------------------------------------------------------------
| Installation Error Page
|--------------------------------------------------------------------------
|
| This function displays an error page when installation directory
| is missing or there are other installation issues.
|
*/
function showInstallationError() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PivotPro HRMS - Installation Error</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .error-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                padding: 3rem;
                text-align: center;
                max-width: 500px;
            }
        </style>
    </head>
    <body>
        <div class="error-card">
            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
            <h2 class="mb-3">Installation Required</h2>
            <p class="text-muted mb-4">
                The installation directory is missing or the application is not properly configured.
                Please ensure the 'install' folder exists in your public directory.
            </p>
            <button onclick="location.reload()" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Retry
            </button>
        </div>
    </body>
    </html>
    <?php
}
?>
