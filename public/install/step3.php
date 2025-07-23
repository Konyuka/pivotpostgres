<?php

// Debug: Check if POST data is being received
if (empty($_POST)) {
    header("location: step2.php?_error=No data received. Please try again.");
    exit;
}

// Debug display (temporarily show what we received)
if (isset($_GET['debug'])) {
    echo "<pre style='background: #f0f0f0; padding: 20px; margin: 20px;'>";
    echo "POST Data Received:\n";
    print_r($_POST);
    echo "\nPurchase Code Details:\n";
    echo "Raw: '" . ($_POST['purchasecode'] ?? 'NOT SET') . "'\n";
    echo "Trimmed: '" . (isset($_POST['purchasecode']) ? trim($_POST['purchasecode']) : 'NOT SET') . "'\n";
    echo "Length: " . (isset($_POST['purchasecode']) ? strlen(trim($_POST['purchasecode'])) : 0) . "\n";
    echo "</pre>";
    echo "<a href='step2.php'>Go Back</a>";
    exit;
}

$purchase_code = isset($_POST['purchasecode']) ? trim($_POST['purchasecode']) : '';
$db_host = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
$db_user = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
$db_password = isset($_POST['dbpass']) ? $_POST['dbpass'] : '';
$db_name = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
$db_port = isset($_POST['dbport']) ? trim($_POST['dbport']) : '5432'; // Default PostgreSQL port

// Debug: Log what we received (for troubleshooting)
error_log("Install Debug - Purchase Code: '" . $purchase_code . "' (length: " . strlen($purchase_code) . ")");
error_log("Install Debug - DB Host: '" . $db_host . "'");
error_log("Install Debug - DB Name: '" . $db_name . "'");
error_log("Install Debug - DB Port: '" . $db_port . "'");

// Validate required fields
if (empty($purchase_code) || empty($db_host) || empty($db_user) || empty($db_name)) {
    header("location: step2.php?_error=Please fill in all required fields.");
    exit;
}

// Local validation
$valid_codes = ['pivotpro', 'PIVOTPRO', 'pivot-pro', 'demo', 'test', 'local'];

if (in_array($purchase_code, $valid_codes)) {
    $object = new \stdClass();
    $object->codecheck = true;

    // Generate SQL from primary migrations for PostgreSQL
    $sql_content = "-- PostgreSQL HRMS Database Setup\n\n";

    // Read migration files from primary folder in order
    $migration_path = '../../database/migrations/primary/';
    $migration_files = glob($migration_path . '*.php');
    sort($migration_files);

    // Convert migrations to SQL
    $sql_content .= generateSqlFromMigrations($migration_files);

    $sql_content .= "\n-- Setup complete\n";

    $object->dbdata = $sql_content;

} else {
    $object = new \stdClass();
    $object->codecheck = false;
}

function generateSqlFromMigrations($files) {
    $sql = "-- HRMS Database Structure Generated from Migrations\n\n";

    // Process each migration file
    foreach ($files as $file) {
        $sql .= "-- Migration: " . basename($file) . "\n";
        $sql .= parseMigrationFile($file);
        $sql .= "\n\n";
    }

    // Add essential data
    $sql .= addEssentialData();

    return $sql;
}

function parseMigrationFile($filePath) {
    $content = file_get_contents($filePath);
    $sql = "";

    // Extract table name from migration
    preg_match('/Schema::create\([\'"]([^\'"]+)[\'"]/', $content, $tableMatches);
    $tableName = isset($tableMatches[1]) ? $tableMatches[1] : 'unknown_table';

    // Basic table structure mapping for PostgreSQL
    $tableStructures = [
        'users' => "
        CREATE TABLE IF NOT EXISTS users (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified_at TIMESTAMP NULL DEFAULT NULL,
            password VARCHAR(255) NOT NULL,
            remember_token VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'companies' => "
        CREATE TABLE IF NOT EXISTS companies (
            id BIGSERIAL PRIMARY KEY,
            company_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(255) DEFAULT NULL,
            website VARCHAR(255) DEFAULT NULL,
            logo VARCHAR(255) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            status VARCHAR(255) DEFAULT 'active',
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'countries' => "
        CREATE TABLE IF NOT EXISTS countries (
            id SERIAL PRIMARY KEY,
            country_code VARCHAR(2) NOT NULL,
            country_name VARCHAR(100) NOT NULL,
            phonecode VARCHAR(10) DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'departments' => "
        CREATE TABLE IF NOT EXISTS departments (
            id BIGSERIAL PRIMARY KEY,
            department_name VARCHAR(255) NOT NULL,
            company_id BIGINT DEFAULT NULL,
            department_head BIGINT DEFAULT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'designations' => "
        CREATE TABLE IF NOT EXISTS designations (
            id BIGSERIAL PRIMARY KEY,
            designation_name VARCHAR(255) NOT NULL,
            department_id BIGINT DEFAULT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'employees' => "
        CREATE TABLE IF NOT EXISTS employees (
            id BIGSERIAL PRIMARY KEY,
            company_id BIGINT DEFAULT NULL,
            emp_id VARCHAR(255) NOT NULL UNIQUE,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(255) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            department_id BIGINT DEFAULT NULL,
            designation_id BIGINT DEFAULT NULL,
            hire_date DATE DEFAULT NULL,
            salary DECIMAL(10,2) DEFAULT NULL,
            status VARCHAR(255) DEFAULT 'active',
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'attendances' => "
        CREATE TABLE IF NOT EXISTS attendances (
            id BIGSERIAL PRIMARY KEY,
            employee_id BIGINT NOT NULL,
            date DATE NOT NULL,
            check_in TIME DEFAULT NULL,
            check_out TIME DEFAULT NULL,
            break_time INTEGER DEFAULT 0,
            working_hours DECIMAL(4,2) DEFAULT NULL,
            overtime_hours DECIMAL(4,2) DEFAULT NULL,
            status VARCHAR(255) DEFAULT 'present',
            notes TEXT DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'leaves' => "
        CREATE TABLE IF NOT EXISTS leaves (
            id BIGSERIAL PRIMARY KEY,
            employee_id BIGINT NOT NULL,
            leave_type VARCHAR(255) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            days INTEGER NOT NULL,
            reason TEXT DEFAULT NULL,
            status VARCHAR(255) DEFAULT 'pending',
            approved_by BIGINT DEFAULT NULL,
            approved_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );",

        'payslips' => "
        CREATE TABLE IF NOT EXISTS payslips (
            id BIGSERIAL PRIMARY KEY,
            employee_id BIGINT NOT NULL,
            month INTEGER NOT NULL,
            year INTEGER NOT NULL,
            basic_salary DECIMAL(10,2) NOT NULL,
            allowances DECIMAL(10,2) DEFAULT 0,
            deductions DECIMAL(10,2) DEFAULT 0,
            gross_salary DECIMAL(10,2) NOT NULL,
            net_salary DECIMAL(10,2) NOT NULL,
            status VARCHAR(255) DEFAULT 'generated',
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        )"
    ];

    // Return the appropriate table structure
    if (isset($tableStructures[$tableName])) {
        return $tableStructures[$tableName];
    }

    // Fallback: create a basic table structure for PostgreSQL
    return "
    CREATE TABLE IF NOT EXISTS {$tableName} (
        id BIGSERIAL PRIMARY KEY,
        created_at TIMESTAMP NULL DEFAULT NULL,
        updated_at TIMESTAMP NULL DEFAULT NULL
    );";
}

function addEssentialData() {
    return "
    -- Essential Data Inserts for PostgreSQL

    -- Default Admin User
    INSERT INTO users (id, name, email, password, created_at, updated_at) VALUES
    (1, 'Admin', 'admin@admin.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW())
    ON CONFLICT (id) DO NOTHING;

    -- Default Company
    INSERT INTO companies (id, company_name, email, status, created_at, updated_at) VALUES
    (1, 'Default Company', 'company@example.com', 'active', NOW(), NOW())
    ON CONFLICT (id) DO NOTHING;

    -- Sample Countries
    INSERT INTO countries (id, country_code, country_name, phonecode) VALUES
    (1, 'US', 'United States', '1'),
    (2, 'UK', 'United Kingdom', '44'),
    (3, 'CA', 'Canada', '1'),
    (4, 'AU', 'Australia', '61'),
    (5, 'KE', 'Kenya', '254')
    ON CONFLICT (id) DO NOTHING;

    -- Default Department
    INSERT INTO departments (id, department_name, company_id, is_active, created_at, updated_at) VALUES
    (1, 'Human Resources', 1, TRUE, NOW(), NOW()),
    (2, 'Information Technology', 1, TRUE, NOW(), NOW()),
    (3, 'Finance', 1, TRUE, NOW(), NOW())
    ON CONFLICT (id) DO NOTHING;

    -- Default Designations
    INSERT INTO designations (id, designation_name, department_id, is_active, created_at, updated_at) VALUES
    (1, 'HR Manager', 1, TRUE, NOW(), NOW()),
    (2, 'Software Developer', 2, TRUE, NOW(), NOW()),
    (3, 'Finance Manager', 3, TRUE, NOW(), NOW())
    ON CONFLICT (id) DO NOTHING;

    -- Set sequences to correct values
    SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
    SELECT setval('companies_id_seq', (SELECT MAX(id) FROM companies));
    SELECT setval('countries_id_seq', (SELECT MAX(id) FROM countries));
    SELECT setval('departments_id_seq', (SELECT MAX(id) FROM departments));
    SELECT setval('designations_id_seq', (SELECT MAX(id) FROM designations));
    ";
}

// Rest of the installation logic remains the same...
if ($object->codecheck) {
    //write in .env
    // Fix: Use absolute path from current script location
    $path = dirname(dirname(__DIR__)) . '/.env';

    // Debug: Check .env file path
    $absolute_path = realpath($path);
    error_log("Install Debug - .env path: " . $path);
    error_log("Install Debug - Current DIR: " . __DIR__);
    error_log("Install Debug - Parent DIR: " . dirname(__DIR__));
    error_log("Install Debug - Laravel ROOT: " . dirname(dirname(__DIR__)));
    error_log("Install Debug - .env absolute path: " . ($absolute_path ? $absolute_path : 'NOT FOUND'));
    error_log("Install Debug - .env exists: " . (file_exists($path) ? 'YES' : 'NO'));    if (!file_exists($path)) {
        // Fallback: Try alternative paths
        $alternative_paths = [
            dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env',
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env',
            realpath(__DIR__ . '/../../.env'),
            'C:\Users\Admin\Documents\OTHERS\hrms\.env' // Direct path based on your file
        ];

        foreach ($alternative_paths as $alt_path) {
            if ($alt_path && file_exists($alt_path)) {
                $path = $alt_path;
                error_log("Install Debug - Found .env at alternative path: " . $path);
                break;
            }
        }

        if (!file_exists($path)) {
            $error_msg = ".env file not found. Tried paths: " . implode(', ', array_merge([$path], $alternative_paths));
            header('location: step2.php?_error=' . urlencode($error_msg));
            exit;
        }
    }

    if (!is_readable($path)) {
        header('location: step2.php?_error=.env file is not readable.');
        exit;
    }
    elseif (!is_writable($path)) {
        header('location: step2.php?_error=.env file is not writable.');
        exit;
    }

    // Read current .env content
    $env_content = file_get_contents($path);

    // Update .env file with PostgreSQL settings (preserve existing content)
    $patterns = array(
        '/^DB_CONNECTION=.*/m',
        '/^DB_HOST=.*/m',
        '/^DB_PORT=.*/m',
        '/^DB_DATABASE=.*/m',
        '/^DB_USERNAME=.*/m',
        '/^DB_PASSWORD=.*/m',
        '/^USER_VERIFIED=.*/m'
    );
    $replacements = array(
        'DB_CONNECTION=pgsql',
        'DB_HOST='.$db_host,
        'DB_PORT='.$db_port,
        'DB_DATABASE='.$db_name,
        'DB_USERNAME='.$db_user,
        'DB_PASSWORD='.$db_password,
        'USER_VERIFIED=1'
    );

    // Apply replacements
    $updated_content = preg_replace($patterns, $replacements, $env_content);

    // Write back to file
    if (file_put_contents($path, $updated_content) === false) {
        header('location: step2.php?_error=Failed to write to .env file.');
        exit;
    }

    //write in database
    try {
        $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
        $dbh = new PDO($dsn, $db_user, $db_password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Execute SQL statements one by one to handle large files
        $statements = preg_split('/;\s*$/m', $object->dbdata);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $dbh->exec($statement);
                } catch (PDOException $e) {
                    // Log the error but continue with other statements
                    error_log("SQL Error: " . $e->getMessage() . " in statement: " . $statement);
                }
            }
        }

        // Create installation marker file
        if (!is_dir('../../storage/app')) {
            mkdir('../../storage/app', 0755, true);
        }
        file_put_contents('../../storage/app/installed.txt', 'Installation completed on ' . date('Y-m-d H:i:s'));

    }
    catch(PDOException $e) {
        if ($e->getCode() == 2002) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure Host info is correct and try again !');
            exit;
        }
        elseif ($e->getCode() == 1045) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure database username and password is correct and try again !');
            exit;
        }
        elseif ($e->getCode() == 1049) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure database name is correct and try again !');
            exit;
        }
        else {
            header('location: step3.php?_error=Database Error: ' . $e->getMessage());
            exit;
        }
    }
} else {
    // Enhanced error message with debugging info
    $error_message = "Invalid Purchase Code! Received: '" . $purchase_code . "' (length: " . strlen($purchase_code) . "). Valid codes: pivotpro, PIVOTPRO, pivot-pro, demo, test, local";
    header("location: step2.php?_error=" . urlencode($error_message));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PivotPro Installer</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .install-body {
            padding: 2rem;
            text-align: center;
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: transform 0.3s ease;
            text-decoration: none;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        .step-number {
            background: #28a745;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <i class="fas fa-check-circle fa-4x mb-3 pulse"></i>
                <h1 class="mb-2">Installation Complete!</h1>
                <p class="mb-0">PivotPro HRMS is ready to use - Step 3 of 3</p>
            </div>

            <div class="install-body">
                <div class="step-number">3</div>
                <h4 class="mb-4">🎉 Congratulations!</h4>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Success!</strong> Your PivotPro HRMS has been installed successfully and is ready to use.
                </div>

                <div class="credentials-box">
                    <h5 class="mb-3">
                        <i class="fas fa-key"></i> Default Login Credentials
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <code class="fs-6">admin@admin.com</code>
                        </div>
                        <div class="col-md-6">
                            <strong>Password:</strong><br>
                            <code class="fs-6">admin</code>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Security Notice:</strong> Please change the default password immediately after your first login.
                </div>

                <div class="mb-4">
                    <a href="<?php echo '../'; ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login to Your HRMS
                    </a>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-trash-alt"></i>
                    <strong>Important:</strong> For security reasons, please delete the 'install' folder from your project directory.
                </div>

                <div class="text-muted">
                    <h6><i class="fas fa-cogs"></i> Next Steps:</h6>
                    <ul class="list-unstyled">
                        <li>✓ Change default password</li>
                        <li>✓ Configure company settings</li>
                        <li>✓ Add departments and employees</li>
                        <li>✓ Setup email configuration</li>
                    </ul>
                </div>
            </div>

            <div class="text-center py-3 border-top">
                <small class="text-muted">Copyright &copy; Techduka. All Rights Reserved.</small>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto-delete install folder
            $.ajax({
                method: 'get',
                url: 'delete.php',
                success: function(response) {
                    if (response == 1) {
                        setTimeout(() => {
                            alert('Please manually delete the "install" folder from your project directory for security.');
                        }, 3000);
                    }
                },
                error: function() {
                    console.log('Could not auto-delete install folder. Please delete manually.');
                }
            });

            // Confetti effect (optional)
            setTimeout(() => {
                console.log('🎉 Installation completed successfully!');
            }, 1000);
        });
    </script>
</body>
</html>
