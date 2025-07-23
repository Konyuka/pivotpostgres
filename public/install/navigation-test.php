<!DOCTYPE html>
<html lang="en">
<head>
    <title>Install System Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Install System Navigation Test</h3>
                    </div>
                    <div class="card-body">
                        <h5>Test Navigation Links</h5>
                        <div class="list-group">
                            <a href="/install/index.php" class="list-group-item list-group-item-action">
                                Step 1 - License Agreement
                            </a>
                            <a href="/install/step2.php" class="list-group-item list-group-item-action">
                                Step 2 - Database Configuration
                            </a>
                            <a href="/install/step3.php" class="list-group-item list-group-item-action">
                                Step 3 - Installation Process
                            </a>
                            <a href="/install/test.php" class="list-group-item list-group-item-action">
                                Test File (Routing Check)
                            </a>
                        </div>

                        <hr>

                        <h5>Debug Information</h5>
                        <div class="alert alert-info">
                            <strong>Current URL:</strong> <?php echo $_SERVER['REQUEST_URI']; ?><br>
                            <strong>Server Name:</strong> <?php echo $_SERVER['SERVER_NAME']; ?><br>
                            <strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?><br>
                            <strong>Install Directory:</strong> <?php echo __DIR__; ?>
                        </div>

                        <h5>Files Check</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $files = ['index.php', 'step2.php', 'step3.php', 'test.php', '.htaccess'];
                                    foreach ($files as $file) {
                                        $exists = file_exists(__DIR__ . '/' . $file);
                                        echo "<tr>";
                                        echo "<td>{$file}</td>";
                                        echo "<td>";
                                        if ($exists) {
                                            echo "<span class='badge bg-success'>Exists</span>";
                                        } else {
                                            echo "<span class='badge bg-danger'>Missing</span>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button onclick="location.reload()" class="btn btn-primary">Refresh Test</button>
                            <a href="../" class="btn btn-secondary">Back to Application</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
