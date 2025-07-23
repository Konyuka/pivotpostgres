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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
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
        .step-number {
            background: #667eea;
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
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .error-field {
            border-color: #dc3545 !important;
            background-color: #ffebee !important;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <i class="fas fa-database fa-3x mb-3"></i>
                <h1 class="mb-2">Database Configuration</h1>
                <p class="mb-0">Auto Installer - Step 2 of 3</p>
            </div>

            <div class="install-body">
                <div class="step-number">2</div>
                <h4 class="mb-4">Setup Your Database Connection</h4>

                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle"></i>
                    <strong>License Accepted!</strong> Now let's configure your database connection.
                </div>

                <?php
                if (isset($_GET['_error'])) {
                    if ($_GET['_error'] != '') {
                        echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> ' . htmlspecialchars($_GET['_error']) . '</div>';
                    }
                }
                ?>

                <form action="/install/step3.php" method="post" id="configForm">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <label class="form-label w-100">
                                    <i class="fas fa-key"></i> Purchase Code
                                    <button type="button" class="btn btn-sm btn-outline-info ms-2" data-bs-toggle="modal" data-bs-target="#purchasecodeModal">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </label>
                                <input type="text" class="form-control" name="purchasecode"
                                       placeholder="Enter 'pivotpro' for local installation"
                                       value="pivotpro" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="form-label w-100">
                                    <i class="fas fa-server"></i> Database Host
                                </label>
                                <input type="text" class="form-control" name="dbhost" value="localhost" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="form-label w-100">
                                    <i class="fas fa-database"></i> Database Name
                                </label>
                                <input type="text" class="form-control" name="dbname" placeholder="hrms" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="form-label w-100">
                                    <i class="fas fa-user"></i> Database Username
                                </label>
                                <input type="text" class="form-control" name="dbuser" value="postgres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="form-label w-100">
                                    <i class="fas fa-lock"></i> Database Password
                                </label>
                                <input type="password" class="form-control" name="dbpass" placeholder="Enter database password">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Make sure your database exists and the credentials are correct.
                        The installer will create all necessary tables automatically.
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-install btn-lg">
                            <i class="fas fa-arrow-right"></i> Install Database
                        </button>

                        <div class="mt-3">
                            <small class="text-muted">
                                Debugging:
                                <button type="button" onclick="debugFormSubmit()" class="btn btn-sm btn-outline-secondary">
                                    Test Form Data
                                </button>
                            </small>
                        </div>
                    </div>
                </form>
            </div>

            <div class="text-center py-3 border-top">
                <small class="text-muted">Copyright &copy; Techduka. All Rights Reserved.</small>
            </div>
        </div>
    </div>

    <!-- Purchase Code Modal -->
    <div class="modal fade" id="purchasecodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> About Purchase Code
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <h6><i class="fas fa-laptop-code"></i> For Local Development:</h6>
                        <p class="mb-0">Use <strong>'pivotpro'</strong> as your purchase code for local installation and testing.</p>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-shopping-cart"></i> For Production Use:</h6>
                        <p class="mb-2">You'll need a valid purchase code from Michael Saiba.</p>
                        <a href="mailto:michaelsaiba84@gmail.com" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('configForm').addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = this.querySelectorAll('input[required]');

            inputs.forEach(function(input) {
                if (input.value.trim() === '') {
                    isValid = false;
                    input.classList.add('error-field');
                } else {
                    input.classList.remove('error-field');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            // Debug: Log form data before submission
            console.log('Form submission data:');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': "' + value + '"');
            }

            // Validate purchase code specifically
            const purchaseCode = document.querySelector('input[name="purchasecode"]').value.trim();
            if (purchaseCode !== 'pivotpro' && purchaseCode !== 'PIVOTPRO' &&
                purchaseCode !== 'pivot-pro' && purchaseCode !== 'demo' &&
                purchaseCode !== 'test' && purchaseCode !== 'local') {
                e.preventDefault();
                alert('Invalid purchase code. Use "pivotpro" for local installation.');
                return false;
            }

            console.log('Form validation passed, submitting...');
        });

        // Debug function to test form submission
        function debugFormSubmit() {
            const form = document.getElementById('configForm');
            const formData = new FormData(form);

            console.log('Debug: Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': "' + value + '"');
            }

            // Create a debug form that adds debug parameter
            const debugForm = document.createElement('form');
            debugForm.method = 'POST';
            debugForm.action = '/install/step3.php?debug=1';
            debugForm.style.display = 'none';

            // Copy all form data
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                debugForm.appendChild(input);
            }

            document.body.appendChild(debugForm);
            debugForm.submit();
        }
    </script>
</body>
</html>
