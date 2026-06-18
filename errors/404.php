<?php
require_once '../functions.php';
$title = "404 Forbidden";
require_once '../partials/header.php';
?>

<body class="hold-transition sidebar-mini">
  <!-- Main content -->
  <section class="content">
    <div class="error-page">
      <h2 class="headline text-warning"> 404</h2>

      <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

        <p>
          We could not find the page you were looking for.
        </p>
      </div>
      <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
  </section>

  <!-- REQUIRED SCRIPTS -->
  <?php require_once '../partials/scripts.php'; ?>

</body>

</html>