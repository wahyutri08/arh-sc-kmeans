 <?php
    if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
        header('HTTP/1.1 403 Forbidden');
        include("../errors/404.html");
        exit();
    }
    ?>

 <footer class="main-footer">
     <!-- To the right -->
     <div class="float-right d-none d-sm-inline">

     </div>
     <!-- Default to the left -->
     <strong>Copyright &copy; <?php echo date('Y', strtotime('now')); ?> BERSAUDARA PRINT</strong>
 </footer>
 <!-- Control Sidebar -->
 <aside class="control-sidebar control-sidebar-dark">
     <!-- Control sidebar content goes here -->
 </aside>
 <!-- /.control-sidebar -->