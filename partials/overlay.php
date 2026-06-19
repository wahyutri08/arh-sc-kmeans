<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.1 403 Forbidden');
    http_response_code(403);
    exit();
}
?>

<div id="pageLoader">
    <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <div class="text-bold pt-2">Processing...</div>
    </div>
</div>