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
            max-width: 700px;
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
            text-decoration: none;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
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
        .license-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            max-height: 300px;
            overflow-y: auto;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .agreement-checkbox {
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <i class="fas fa-file-contract fa-3x mb-3"></i>
                <h1 class="mb-2">License Agreement</h1>
                <p class="mb-0">Auto Installer - Step 1 of 3</p>
            </div>

            <div class="install-body">
                <div class="step-number">1</div>
                <h4 class="mb-4">PivotPro HRMS License Terms</h4>

                <div class="license-box mb-4">
                    <h6><strong>STANDARD LICENSE AGREEMENT</strong></h6>
                    <p><strong>Grant of License:</strong> This license grants you the right to use PivotPro HRMS for personal or commercial projects.</p>

                    <p><strong>Permitted Uses:</strong></p>
                    <ul>
                        <li>Use in a single commercial or personal project</li>
                        <li>Modify the code to suit your requirements</li>
                        <li>Create derivative works for your own use</li>
                        <li>Use for client projects (with proper licensing)</li>
                    </ul>

                    <p><strong>Restrictions:</strong></p>
                    <ul>
                        <li>You may not redistribute or resell the source code</li>
                        <li>You may not create competing products using this code</li>
                        <li>Attribution must be maintained in the source code</li>
                        <li>No warranty is provided - use at your own risk</li>
                    </ul>

                    <p><strong>Support:</strong> Limited support is available through our support channels. Installation support may incur additional charges.</p>

                    <p><strong>Updates:</strong> Updates are provided for bug fixes and security issues. Feature updates may require license renewal.</p>

                    <p><strong>Termination:</strong> This license is valid until terminated. It will terminate automatically if you fail to comply with any terms.</p>

                </div>

                <form action="/install/step2.php" method="GET" id="licenseForm">
                    <div class="agreement-checkbox">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="acceptLicense" required>
                            <label class="form-check-label" for="acceptLicense">
                                <strong>I have read and agree to the license terms above</strong>
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Local Installation:</strong> Use purchase code <strong>'pivotpro'</strong> for development and testing purposes.
                    </div>

                    <!-- Debug Information -->
                    <div class="alert alert-secondary small" id="debugInfo">
                        <strong>Debug Info:</strong><br>
                        Current URL: <span id="currentUrl"></span><br>
                        Target URL: <span id="targetUrl"></span><br>
                        Install Path: <span id="installPath"></span>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-install btn-lg" id="continueBtn" disabled>
                            <i class="fas fa-arrow-right"></i> Continue to Database Setup
                        </button>

                    </div>
                </form>

                <hr class="my-4">

                <div class="text-muted text-center">
                    <small>
                        <i class="fas fa-life-ring"></i>
                        Need help? Contact: <a href="mailto:support@techduka.co.ke">support@techduka.co.ke</a>
                    </small>
                </div>
            </div>

            <div class="text-center py-3 border-top">
                <small class="text-muted">Copyright &copy; Techduka. All Rights Reserved.</small>
            </div>
        </div>
    </div>

    <script src="./assets/js/install-nav.js"></script>
    <script>
        // Initialize debug information
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            const baseUrl = window.location.origin;
            const installPath = '/install/';
            const targetUrl = baseUrl + installPath + 'step2.php';

            document.getElementById('currentUrl').textContent = currentUrl;
            document.getElementById('targetUrl').textContent = targetUrl;
            document.getElementById('installPath').textContent = installPath;
        });

        // Enable/disable continue button based on checkbox
        document.getElementById('acceptLicense').addEventListener('change', function() {
            const continueBtn = document.getElementById('continueBtn');
            const directLinkBtn = document.getElementById('directLinkBtn');

            if (this.checked) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('btn-secondary');
                continueBtn.classList.add('btn-install');
                directLinkBtn.style.display = 'inline-block';
            } else {
                continueBtn.disabled = true;
                continueBtn.classList.remove('btn-install');
                continueBtn.classList.add('btn-secondary');
                directLinkBtn.style.display = 'none';
            }
        });

        // Form validation and enhanced navigation using InstallNav
        document.getElementById('licenseForm').addEventListener('submit', function(e) {
            const checkbox = document.getElementById('acceptLicense');
            if (!checkbox.checked) {
                e.preventDefault();
                alert('Please read and accept the license agreement to continue.');
                return false;
            }

            // Prevent default form submission and use enhanced navigation
            e.preventDefault();

            // Use the InstallNav helper for robust navigation
            InstallNav.navigateToStep('step2.php');
        });

        // Handle direct link click
        document.getElementById('directLinkBtn').addEventListener('click', function(e) {
            e.preventDefault();
            InstallNav.navigateToStep('step2.php');
        });
    </script>
</body>
</html>
