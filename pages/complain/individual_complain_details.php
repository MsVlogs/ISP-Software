<?php
$complainId = filter_input(INPUT_GET, 'complain_id', FILTER_VALIDATE_INT);
$complainId = ($complainId !== false && $complainId !== null && $complainId > 0) ? $complainId : 0;
$allComplain = $obj->raw_sql("SELECT COUNT(id) as totalComplain FROM tbl_complains WHERE complain_type = $complainId AND deleted_at IS NULL");
$pendingComplains = $obj->raw_sql("SELECT COUNT(id) as totalPendingComplains FROM tbl_complains WHERE complain_type = $complainId AND status = 1 AND deleted_at IS NULL");
$processingComplains = $obj->raw_sql("SELECT COUNT(id) as totalProcessingComplains FROM tbl_complains WHERE complain_type = $complainId AND status = 2 AND deleted_at IS NULL");
$solvedComplains = $obj->raw_sql("SELECT COUNT(id) as totalSolvedComplains FROM tbl_complains WHERE complain_type = $complainId AND status = 3 AND deleted_at IS NULL");
?>

<style>
    .table-responsive {
        overflow-x: auto;
        width: auto;
    }
</style>
<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        <div class="mt-24">
            <div class="row mt-24 gy-0">
                <div class="col-xxl-3 col-sm-6">
                    <div class="card p-3 shadow-none radius-8 border h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span class="mb-0 w-48-px h-48-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                        <iconify-icon icon="mdi:alert-octagon" class="icon"></iconify-icon>
                                    </span>
                                    <div>
                                        <p class="mb-2 fw-medium text-secondary-light text-sm">Total Complains : </p>
                                        <h6 class="fw-semibold"><span data-plugin="counterup"><?php echo $allComplain[0]["totalComplain"]; ?></span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card p-3 shadow-none radius-8 border h-100 bg-gradient-end-3">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span class="mb-0 w-48-px h-48-px bg-danger-main flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6">
                                        <iconify-icon icon="mdi:clock" class="icon"></iconify-icon>
                                    </span>
                                    <div>
                                        <p class="mb-2 fw-medium text-secondary-light text-sm">Pending Complains : </p>
                                        <h6 class="fw-semibold"><span id="credit_total"><?php echo $pendingComplains[0]["totalPendingComplains"]; ?></span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card p-3 shadow-none radius-8 border h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span class="mb-0 w-48-px h-48-px bg-primary text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                        <iconify-icon icon="mdi:cogs" class="icon"></iconify-icon>
                                    </span>
                                    <div>
                                        <p class="mb-2 fw-medium text-secondary-light text-sm">Processing Complains : </p>
                                        <h6 class="fw-semibold"><span id="debit_total"><?php echo $processingComplains[0]["totalProcessingComplains"]; ?></span></h6>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card p-3 shadow-none radius-8 border h-100 bg-gradient-end-2">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                <div class="d-flex align-items-center gap-2">
                                    <span class="mb-0 w-48-px h-48-px bg-success text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                        <iconify-icon icon="mdi:check-decagram" class="icon"></iconify-icon>
                                    </span>
                                    <div>
                                        <p class="mb-2 fw-medium text-secondary-light text-sm">Solved Complains : </p>
                                        <h6 class="fw-semibold"><span><?php echo $solvedComplains[0]["totalSolvedComplains"]; ?></span></h6>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <!-- Form Wizard Start -->
            <div class="form-wizard">
                <fieldset class="wizard-fieldset show">
                    <h6 class="text-md text-neutral-500">Customer List</h6>
                    <div class="row gy-3">
                        <div class="col-sm-3">
                            <label for="customer-filter" class="form-label">Customer</label>
                            <div class="position-relative">
                                <select id="customer-filter" class="form-control wizard-required">
                                    <option value="">Select</option>
                                    <?php foreach ($obj->getAllData('vw_agent') as $user): ?>
                                        <option value="<?php echo $user['ag_id']; ?>"><?php echo $user['ag_name']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="complain-category-filter" class="form-label">Complain Category</label>
                            <div class="position-relative">
                                <select id="complain-category-filter" class="form-control wizard-required">
                                    <option value="">Select</option>
                                    <?php foreach ($obj->getAllData('tbl_complain_templates') as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['template']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="assign-filter" class="form-label">Assign To</label>
                            <div class="position-relative">
                                <select id="assign-filter" class="form-control wizard-required">
                                    <option value="">Select</option>
                                    <?php foreach ($obj->getAllData('tbl_employee') as $employee): ?>
                                        <option value="<?php echo $employee['id']; ?>"><?php echo $employee['employee_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="status-filter" class="form-label">Status</label>
                            <div class="position-relative">
                                <select id="status-filter" class="form-control wizard-required">
                                    <option value="">Select</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Processing</option>
                                    <option value="3">Solved</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="priority-filter" class="form-label">Priority</label>
                            <div class="position-relative">
                                <select id="priority-filter" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">High</option>
                                    <option value="2">Medium</option>
                                    <option value="3">Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="date-from" class="form-label">From Date</label>
                            <div class="position-relative">
                                <input type="date" id="date-from" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="date-to" class="form-label">To Date</label>
                            <div class="position-relative">
                                <input type="date" id="date-to" class="form-control">
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!-- Form Wizard End -->
        </div>
    </div>
</div>

<div class="card basic-data-table">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title mb-0">Complains Details List</h5>
    </div>
    <div class="card-body table-responsive">
        <table
            class="table bordered-table mb-0"
            id="complain-datatable"
            data-page-length="10">
            <thead>
                <tr>
                    <th scope="col">Sl.</th>
                    <th scope="col">Name</th>
                    <th scope="col">IP</th>
                    <th scope="col">Address</th>
                    <th scope="col">Mobile No</th>
                    <th scope="col">Problem</th>
                    <th scope="col">Details</th>
                    <th scope="col">Complain Date</th>
                    <th scope="col">Solve By</th>
                    <th scope="col">Solve Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- end row-->

<!-- Center modal content -->
<div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Change Status</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <input type="hidden" id="modalComplainId">
                        <label for="ComplainStatus" class="form-label">Status</label>
                        <select id="ComplainStatus" class="form-select">
                            <option value="">Choose</option>
                            <option value="1">Pending</option>
                            <option value="2">Processing</option>
                            <option value="3">Solved</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="changedStatus" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- delete modal content -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Are you sure you want to delete this complaint?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="deleteComplainId">
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="deleteComplain" class="btn btn-danger">Yes Delete</button>
            </div>
        </div>
    </div>
</div> <!-- end delete modal -->


<?php $obj->start_script(); ?>
<script>
    $(document).ready(function() {
        var table = $('#complain-datatable').DataTable({
            dom: `<"row"<"col-sm-6"l><"col-sm-6 text-end"f>>` + // Show entries and search in one row
                `<"row"<"col-sm-12 text-end"B>>` + // Buttons in a separate row
                `<"row dt-layout-row"<"col-sm-12"tr>>` + // Table content
                `<"row"<"col-sm-5"i><"col-sm-7 text-end"p>>`, // Info left, pagination right,
            keys: true,
            stateSave: true,
            responsive: true,
            pagingType: "full_numbers",
            paging: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "./pages/complain/getIndividualComplainAjax.php", // URL to your PHP file
                type: "GET",
                data: function(d) {
                    d.category = $('#complain-category-filter').val(); // Get category filter value
                    d.status = $('#status-filter').val(); // Get status filter
                    d.prioirity = $('#priority-filter').val(); // Get priority filter
                    d.assgn = $('#assign-filter').val(); // Get assign filter
                    d.datefrom = $('#date-from').val(); // Get date from filter
                    d.dateto = $('#date-to').val(); // Get date to filter
                    d.customer = $('#customer-filter').val(); // Get Customer Name to filter
                    d.individual_complian_id = <?= $_GET['complain_id'] ?>;
                }
            },
            columns: [{
                    data: 'sl',

                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'customer_ip'
                },
                {
                    data: 'address'
                },
                {
                    data: 'phone',

                }, {
                    data: 'complain_name',

                },
                {
                    data: 'details'
                },
                {
                    data: 'complain_date',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('en-US', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }).replace(',', '') : '';
                    }
                },
                {
                    data: 'solve_by'
                },
                {
                    data: 'solve_date',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('en-US', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }).replace(',', '') : '';
                    }
                },
                {
                    data: 'status_name',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return "<span class='text-danger'>Pending</span>";
                        } else if (data == 2) {
                            return "<span class='text-info'>Processing</span>";
                        } else if (data == 3) {
                            return "<span class='text-success'>Solve</span>";
                        } else {
                            return "<span class='text-Warning'>Not Solve</span>";
                        }
                    }
                },
                {
                    data: 'priority_name',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return "<span class='text-danger'>High</span>";
                        } else if (data == 2) {
                            return "<span class='text-warning'>Medium</span>";
                        } else if (data == 3) {
                            return "<span class='text-info'>Low</span>";
                        } else {
                            return "<span class='text-info'>Default</span>";
                        }
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                        <a href="?page=edit_complain&id=` + data + `"  class='btn btn-primary waves-effect waves-light btn-sm'>Edit</a>
                         <a data-bs-toggle="modal" href="#centermodal"  data-id="${row.id}" class='btn btn-success waves-effect waves-light btn-sm status-modal'>Status</a>
                         <a data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-id="${row.id}"  class='btn btn-danger waves-effect waves-light btn-sm delete-modal'>Delete</a>`;
                    }
                }
            ],
            buttons: [{
                extend: "pdf",
                className: "btn-success",
                text: "Print Pdf",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                customize: function(doc) {
                    // Get filter values
                    var category = $('#complain-category-filter').val() || null;
                    var status = $('#status-filter').val() || null;
                    var priority = $('#priority-filter').val() || null;
                    var assign = $('#assign-filter').val() || null;
                    var customer = $('#customer-filter').val() || null;
                    var dateFrom = $('#date-from').val() ? new Date($('#date-from').val()).toLocaleDateString() : null;
                    var dateTo = $('#date-to').val() ? new Date($('#date-to').val()).toLocaleDateString() : null;

                    // Initialize the title with a base string
                    var title = 'Complaints Report:';

                    // Conditionally append each filter if it is set with meaningful sentences
                    if (category) {
                        title += ` Showing complaints from the "${$('#complain-category-filter option:selected').text()}" category.`;
                    }

                    if (status) {
                        title += ` Filtering complaints with the status of "${$('#status-filter option:selected').text()}".`;
                    }

                    if (priority) {
                        title += ` Including complaints marked as "${$('#priority-filter option:selected').text()}" priority.`;
                    }

                    if (assign) {
                        title += ` Showing complaints assigned to "${$('#assign-filter option:selected').text()}".`;
                    }

                    if (customer) {
                        title += ` Showing complaints submitted by ${$('#customer-filter option:selected').text()}.`;
                    }

                    // Handle date range if both are provided
                    if (dateFrom && dateTo) {
                        title += ` Complaints from the period starting on ${dateFrom} and ending on ${dateTo}.`;
                    } else if (dateFrom) {
                        title += ` Complaints starting from ${dateFrom}.`;
                    } else if (dateTo) {
                        title += ` Complaints up until ${dateTo}.`;
                    }

                    // If no filter is applied, provide a default statement
                    if (!category && !status && !priority && !assign && !customer && !dateFrom && !dateTo) {
                        title = 'Complaints Report: Showing all complaints without filters applied.';
                    }

                    // Update the title in the PDF
                    doc.content.splice(0, 1, {
                        text: title,
                        fontSize: 16,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 0, 0, 20], // [left, top, right, bottom]
                    });

                    // Footer and table styles remain the same
                    doc['footer'] = function(currentPage, pageCount) {
                        return {
                            columns: [{
                                    text: `Generated on: ${new Date().toLocaleDateString()}`,
                                    alignment: 'left',
                                    fontSize: 8,
                                },
                                {
                                    text: `Page ${currentPage} of ${pageCount}`,
                                    alignment: 'right',
                                    fontSize: 8,
                                },
                            ],
                            margin: [10, 0],
                        };
                    };

                    var objLayout = {};
                    objLayout['hLineWidth'] = function(i) {
                        return 0.5;
                    };
                    objLayout['vLineWidth'] = function(i) {
                        return 0.5;
                    };
                    objLayout['hLineColor'] = function(i) {
                        return '#aaa';
                    };
                    objLayout['vLineColor'] = function(i) {
                        return '#aaa';
                    };
                    objLayout['paddingLeft'] = function(i) {
                        return 4;
                    };
                    objLayout['paddingRight'] = function(i) {
                        return 4;
                    };
                    doc.content[1].layout = objLayout;
                },


            }],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search complaints...",
                lengthMenu: "Show _MENU_ entries",
                emptyTable: "No data available in table"
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            lengthMenu: [10, 25, 50, 100, 500],
            order: [
                [1, 'asc'] // Order by 'customer_name' or adjust as needed
            ],
            initComplete: function(settings, json) {
                // Set total counts after the table has been initialized
                $('#totalcomplaints').text(json.totalcomplaints || 0);
                $('#totalresolved').text(json.totalresolved || 0);
            },
            drawCallback: function(settings) {
                var json = settings.json; // Get the JSON response

                // Update total complaint and resolved counts after each table draw
                $('#totalcomplaints').text(json.totalcomplaints || 0);
                $('#totalresolved').text(json.totalresolved || 0);
            }
        });

        // Reload table on filter change
        $('#complain-category-filter, #status-filter, #priority-filter, #assign-filter, #date-from, #date-to,#customer-filter').on('change', function() {
            table.ajax.reload();
        });

        $('#changedStatus').on('click', function() {
            // Collect data
            const complainId = $('#modalComplainId').val();
            const statusId = $('#ComplainStatus').val();

            // Validate status selection
            if (!statusId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please select a status'
                });
                return;
            }

            // Prepare AJAX data
            const formData = {
                id: complainId,
                status: statusId
            };

            console.log(formData);


            // AJAX request
            $.ajax({
                url: './pages/complain/update_status.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: complainId,
                    status: statusId
                }),
                dataType: 'json',
                success: function(response) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Status Updated',
                    //     text: response.message
                    // });
                    $('#centermodal').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Update Failed',
                    //     text: xhr.responseJSON ? xhr.responseJSON.message : 'An unexpected error occurred'
                    // });
                    console.log(xhr.responseJSON ? xhr.responseJSON.message : 'An unexpected error occurred');

                }
            });
        });

        // delete complain
        $('#deleteComplain').on('click', function() {
            const deleteComplainId = $('#deleteComplainId').val();

            // AJAX request
            $.ajax({
                url: './pages/complain/delete_complain.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    complain_id: deleteComplainId
                }),
                dataType: 'json',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON ? xhr.responseJSON.message : 'An unexpected error occurred');
                }
            });
        });


        $(document).on('click', '.delete-modal', function() {
            // Get the ID from the clicked button
            const complainDeleteId = $(this).data('delete-id');
            $('#deleteComplainId').val(complainDeleteId)
        });

        $(document).on('click', '.status-modal', function() {
            // Get the ID from the clicked button
            const complainId = $(this).data('id');

            // Set the ID in a hidden input in the modal
            $('#modalComplainId').val(complainId);

            // Reset status dropdown
            $('#ComplainStatus').val('');
        });

        // Adjust columns after initialization
        table.columns.adjust().responsive.recalc();
    });
</script>

<?php $obj->end_script(); ?>