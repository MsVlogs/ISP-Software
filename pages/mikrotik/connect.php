<link rel="stylesheet" href="assets/css/isp-mikrotik-v2.css">
<div class="isp-mikrotik-v2">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <!-- Form Wizard Start -->
            <div class="form-wizard">
                <fieldset class="wizard-fieldset show">
                    <h6 class="text-md text-neutral-500">Create New Mikrotik</h6>
                    <div class="row gy-3">
                        <div class="col-sm-3">
                            <label class="form-label">User Name*</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" name="username" id="username" placeholder="Enter Username" required>
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Password*</label>
                            <div class="position-relative">
                                <input type="password" class="form-control wizard-required" name="password" id="password" placeholder="Enter Password" required>
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">IP*</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" name="ip" id="ip" placeholder="Enter IP" required>
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label">Port*</label>
                            <div class="position-relative">
                                <select class="form-control wizard-required" name="port" id="port">
                                    <option value="">Select</option>
                                    <option value="8721">8721</option>
                                    <option value="8725">8725</option>
                                    <option value="8728">8728</option>
                                    <option value="9000">9000</option>
                                </select>
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <label class="form-label"></label>
                            <button type="button" class="btn btn-success-600 radius-8 p-20 w-60-px h-50-px d-flex align-items-center justify-content-center gap-2" id="save-button">
                                <iconify-icon icon="mdi:plus" class="text-2xl"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!-- Form Wizard End -->
        </div>
    </div>
</div>
<div class="card basic-data-table mikrotik-v2-panel">
    <div class="card-header mikrotik-v2-header">
        <div><h5 class="card-title mb-1">MikroTik Servers</h5><small class="text-secondary">Manage router access, availability and live connectivity</small></div>
        <div class="mikrotik-v2-toolbar"><select id="mikrotikStatusFilter" class="form-select form-select-sm"><option value="">All Status</option><option value="^Enable$">Active</option><option value="^Disabled$">Inactive</option></select><input id="mikrotikSearch" class="form-control form-control-sm" placeholder="Search MikroTik..." /></div>
    </div>
    <div class="card-body">
        <table
            class="table bordered-table mb-0"
            id="mikrotikTable"
            data-page-length="10">
            <thead>
                <tr>
                    <th scope="col">SL.No</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Password</th>
                    <th scope="col">IP</th>
                    <th scope="col">Port</th>
                    <th scope="col">Status</th>
                    <th scope="col">Connection Status</th>
                    <?php if ($obj->userWorkPermission('edit')) { ?>
                        <th scope="col">Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Mikrotik Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="mik_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="edit-password" name="mik_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-ip" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="edit-ip" name="mik_ip" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-port" class="form-label">Port</label>
                        <input type="text" class="form-control" id="edit-port" name="mik_port" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success waves-effect waves-light" id="saveEditButton">Save Changes</button>
            </div>
        </div>
    </div>
</div>


</div>
<?php $obj->start_script(); ?>
<script>
    $(document).ready(function() {
        // Define the mkConnectCheck function within the ready function or globally if required
        function mkConnectCheck(id) {
            $.ajax({
                type: "GET",
                url: "./pages/mikrotik/connect_ajax.php",
                data: {
                    'mkid': id
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.connection) {
                        $(`#m${id}`).html(`<button class="btn btn-success" type="button">${response.status}</button>`);
                    } else {
                        $(`#m${id}`).html(`<button class="btn btn-danger" type="button">${response.status}</button>`);
                    }
                },
                error: function() {
                    $(`#m${id}`).html(`<button class="btn btn-danger" type="button">Error</button>`);
                }
            });
        }

        // Initialize DataTable
        $('#mikrotikTable').DataTable({
            "ajax": "./pages/mikrotik/mikrotik.php",
            "processing": true,
            "serverSide": false,
            "responsive": true,
            "paging": true, // Enable pagination
            "searching": true, // Enable searching
            "info": true,
            "ordering": true,

            "columns": [{
                    "data": "sl"
                },
                {
                    "data": "mik_username"
                },
                {
                    "data": "mik_password",
                    "render": function(data) { return `<span class="mik-password" data-password="${String(data).replace(/"/g, '&quot;')}">••••••••</span> <button type="button" class="btn btn-link btn-sm p-0 mik-password-toggle" title="Show password"><iconify-icon icon="lucide:eye"></iconify-icon></button>`; }
                },
                {
                    "data": "mik_ip"
                },
                {
                    "data": "mik_port"
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return data === "Enable" ? `<span class="mik-status active"><span></span>Active</span>` : `<span class="mik-status inactive"><span></span>Inactive</span>`;
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return `<span id="m${data}">
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading...
                        </button>
                    </span>`;
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        const inactive = row.status === "Disabled";
                        const toggleIcon = inactive ? "mdi:play-circle-outline" : "mdi:pause-circle-outline";
                        const toggleClass = inactive ? "mik-action-enable" : "mik-action-disable";
                        const toggleTitle = inactive ? "Activate" : "Deactivate";
                        return `<div class="mik-actions">
                            <button type="button" class="mik-action mik-action-edit edit-btn" data-id="${row.id}" title="Edit"><iconify-icon icon="lucide:edit"></iconify-icon></button>
                            <button type="button" class="mik-action ${toggleClass} mik-toggle-btn" data-id="${row.id}" data-active="${inactive ? 0 : 1}" title="${toggleTitle}"><iconify-icon icon="${toggleIcon}"></iconify-icon></button>
                            <button type="button" class="mik-action mik-action-delete delete-btn" data-id="${row.id}" title="Delete"><iconify-icon icon="mdi:trash-can-outline"></iconify-icon></button>
                        </div>`;
                    }
                },
            ],
            "responsive": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });

        const mikTable = $('#mikrotikTable').DataTable();
        $('#mikrotikSearch').on('input', function() { mikTable.search(this.value).draw(); });
        $('#mikrotikStatusFilter').on('change', function() { mikTable.column(5).search(this.value, true, false).draw(); });

        $(document).on('click', '.mik-password-toggle', function() {
            const el = $(this).prev('.mik-password');
            const shown = el.hasClass('revealed');
            el.text(shown ? '••••••••' : el.data('password')).toggleClass('revealed', !shown);
            $(this).attr('title', shown ? 'Show password' : 'Hide password');
        });

        // Call the connection check function for each row after table draw
        $('#mikrotikTable').on('xhr.dt', function(e, settings, json) {
            if (json && json.data) {
                json.data.forEach(function(row) {
                    mkConnectCheck(row.id);
                });
            }
        });
        $('#save-button').click(function(e) {
            e.preventDefault();
            // Collect values from the input fields
            var username = $('#username').val().trim();
            var password = $('#password').val().trim();
            var ip = $('#ip').val().trim();
            var port = $('#port').val().trim();

            // Validation flags
            var isValid = true;


            if (!ip) {
                Swal.fire({
                    title: 'Validation Error',
                    text: 'IP Address is required.',
                    icon: 'error',
                    // toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
                isValid = false;
            }
            if (!password) {
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Password is required.',
                    icon: 'error',
                    // toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
                isValid = false;
            }
            // Validate input fields
            if (!username) {
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Username is required.',
                    icon: 'error',
                    // toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
                isValid = false;
            }

            if (isValid) {
                var data = {
                    username: username,
                    password: password,
                    ip: ip,
                    port: port
                };

                // Send the data via AJAX to the server
                $.ajax({
                    url: './pages/mikrotik/connect_update_ajax.php', // Adjust to your server-side script URL
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success == false) {
                            Swal.fire(
                                'Error!',
                                response.status,
                                'error'
                            );
                            $('#username').val('');
                            $('#password').val('');
                            $('#ip').val('');
                            $('#port').val('');
                        } else if (response.success == true) {
                            Swal.fire(
                                'Success',
                                response.status,
                                'success'
                            );
                            $('#username').val('');
                            $('#password').val('');
                            $('#ip').val('');
                            $('#port').val('');

                            $('#mikrotikTable').DataTable().ajax.reload();
                            mkConnectCheck(id); // Call mkConnectCheck with the ID
                        } else {
                            Swal.fire(
                                'Error!',
                                'Something went wrong. Please try again later.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again later.',
                            'error'
                        );
                    }
                });
            }
        });
        $(document).on('click', '.mik-toggle-btn', function() {
            const id = $(this).data('id');
            const active = Number($(this).data('active')) ? 0 : 1;
            const label = active ? 'activate' : 'deactivate';
            Swal.fire({title: `Confirm ${label}`, text: `Are you sure you want to ${label} this MikroTik?`, icon: 'question', showCancelButton: true, confirmButtonText: 'Yes'}).then(function(result) {
                if (!result.isConfirmed) return;
                $.ajax({type:'POST',url:'./pages/mikrotik/toggle_mikrotik.php',data:{id:id,status:active},dataType:'JSON',success:function(response){
                    Swal.fire(response.success ? 'Updated!' : 'Error', response.status, response.success ? 'success' : 'error');
                    if(response.success) $('#mikrotikTable').DataTable().ajax.reload(null,false);
                },error:function(){ Swal.fire('Error','Unable to update MikroTik status.','error'); }});
            });
        });

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({title:'Delete MikroTik?',text:'This permanently removes the server record.',icon:'warning',showCancelButton:true,confirmButtonText:'Delete',confirmButtonColor:'#dc2626'}).then(function(result){
                if(!result.isConfirmed) return;
                $.ajax({type:'GET',url:'./pages/mikrotik/delete_mikrotik.php',data:{id:id},success:function(){
                    Swal.fire('Deleted','MikroTik server deleted successfully.','success');
                    $('#mikrotikTable').DataTable().ajax.reload(null,false);
                },error:function(){Swal.fire('Error','Unable to delete MikroTik server.','error');}});
            });
        });

        $('#mikrotikTable').on('click', '.edit-btn', function() {
            var id = $(this).data('id');

            // Fetch data using AJAX
            $.ajax({
                url: './pages/mikrotik/connect_update_ajax.php',
                type: 'GET',
                data: {
                    mkid: id
                },
                success: function(response) {
                    // Assuming the response is JSON with the data
                    $('#edit-id').val(response.id);
                    $('#edit-username').val(response.mik_username);
                    $('#edit-password').val(response.mik_password);
                    $('#edit-ip').val(response.mik_ip);
                    $('#edit-port').val(response.mik_port);

                    // Show the modal
                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('Error fetching data: ' + error);
                }
            });
        });

        $('#saveEditButton').click(function() {
            var id = $('#edit-id').val(); // Get the ID from the hidden input (read-only)
            var username = $('#edit-username').val(); // Get the new username
            var password = $('#edit-password').val(); // Get the new password
            var ip = $('#edit-ip').val(); // Get the new IP
            var port = $('#edit-port').val(); // Get the new port
            // Send the updated data via AJAX
            console.log(id);
            $.ajax({
                url: './pages/mikrotik/connect_update_ajax.php', // The same or another endpoint for updating
                type: 'POST', // POST request to send updated data
                data: {
                    mkid: id,
                    mik_username: username,
                    mik_password: password,
                    mik_ip: ip,
                    mik_port: port
                },
                success: function(response) {
                    // Handle the response (e.g., show a success message or refresh the table)
                    Swal.fire(
                        'Updated!',
                        'Your Mikrotik has been updated.',
                        'success'
                    );
                    $('#editModal').modal('hide'); // Hide the modal
                    $('#mikrotikTable').DataTable().ajax.reload(); // Reload the table to reflect changes

                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong. Please try again later.',
                        'error'
                    );
                }
            });
        });

    });
</script>
<?php $obj->end_script(); ?>
