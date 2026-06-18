<?php
require_once '../functions.php';
$title = "404 Forbidden";
require_once '../partials/header.php';
?>

<body class="hold-transition sidebar-mini">
    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger">500</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>
                <p>
                    We will work on fixing that right away.
                </p>
            </div>
        </div>
        <!-- /.error-page -->
    </section>

    <!-- REQUIRED SCRIPTS -->
    <?php require_once '../partials/scripts.php'; ?>

</body>

</html>