 <!-- <?php foreach ($history as $iteration): ?>
        <script>
            $(function() {
                $('#example<?= $iteration['iteration'] ?>').DataTable({
                    paging: true,
                    lengthChange: true,
                    pageLength: 100,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: true,
                    responsive: false
                });
                $('#exampleIterasi<?= $iteration['iteration'] ?>').DataTable({
                    paging: true,
                    lengthChange: true,
                    pageLength: 100,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: true,
                    responsive: false
                });
            });
        </script>
    <?php endforeach; ?> -->