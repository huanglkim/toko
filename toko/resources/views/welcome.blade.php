<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable with Arrow Key Navigation</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        /* Highlight the focused row for keyboard navigation */
        #statusTable tbody tr:focus {
            outline: 2px solid #007bff;
            outline-offset: -2px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h3>DataTable with Arrow Key Navigation</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#datatableModal">
            Select Status
        </button>
        <div class="form-group mt-3">
            <label for="selectedStatus">Selected Status:</label>
            <input type="hidden" id="selectedStatus" name="status" />
            <div id="selectedStatusText">None</div>
        </div>
    </div>

    <!-- Modal with DataTable -->
    <div class="modal fade" id="datatableModal" tabindex="-1" aria-labelledby="datatableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="datatableModalLabel">Select Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="statusTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-value="1" tabindex="0">
                                <td>AKTIF</td>
                                <td>Not OK</td>
                            </tr>
                            <tr data-value="0" tabindex="0">
                                <td>NON AKTIF</td>
                                <td>OK</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#statusTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                dom: 't', // Only show the table
            });

            // Handle row selection with mouse
            $('#statusTable tbody').on('click', 'tr', function() {
                selectRow($(this));
            });

            // Add arrow key navigation and Enter key for selection
            $('#statusTable tbody').on('keydown', 'tr', function(e) {
                const currentRow = $(this);

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextRow = currentRow.next('tr');
                    if (nextRow.length) {
                        nextRow.focus();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevRow = currentRow.prev('tr');
                    if (prevRow.length) {
                        prevRow.focus();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    selectRow(currentRow);
                }
            });

            // Focus the first row when the modal is opened
            $('#datatableModal').on('shown.bs.modal', function() {
                const firstRow = $('#statusTable tbody tr:first-child');
                firstRow.focus();
            });

            // Function to select a row and update the selected value
            function selectRow(row) {
                const value = row.data('value');
                const text = row.find('td:first').text();

                // Update the hidden input and display the selected value
                $('#selectedStatus').val(value);
                $('#selectedStatusText').text(text);

                // Close the modal
                $('#datatableModal').modal('hide');
            }
        });
    </script>
</body>

</html>
