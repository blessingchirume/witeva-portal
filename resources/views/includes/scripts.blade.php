<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function() {
        $("#employeeTable").DataTable({
            "scrollY": "400px",
            "lengthChange": true,
            "paging": true,
            "responsive": false,
            "autoWidth": true,
            "lengthMenu": [
                [10, 20, 50, 100, 200, 300, -1],
                [10, 20, 50, 100, 200, 300, 'All']
            ],
        });
    });
</script>