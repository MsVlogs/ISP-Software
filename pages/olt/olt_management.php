<?php
// OLT Management Page - Add, Edit, Delete OLT Devices

// Get all OLT devices
$oltDevices = $obj->view_all('tbl_olt_devices') ?? [];

// Handle Add OLT
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $oltName = trim($_POST['olt_name'] ?? '');
    $oltIp = trim($_POST['olt_ip'] ?? '');
    $oltPort = trim($_POST['olt_port'] ?? '161');
    $community = trim($_POST['community'] ?? 'public');
    $description = trim($_POST['description'] ?? '');

    if ($oltName && $oltIp) {
        $data = [
            'device_name' => $oltName,
            'ip_address' => $oltIp,
            'snmp_port' => (int) $oltPort,
            'read_community' => $community,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($obj->insertData('tbl_olt_devices', $data)) {
            $obj->notificationStore('OLT Device Added Successfully', 'success');
            echo '<script>setTimeout(() => { location.reload(); }, 1000);</script>';
        } else {
            $obj->notificationStore('Failed to Add OLT Device', 'error');
        }
    } else {
        $obj->notificationStore('Please fill all required fields', 'warning');
    }
}

// Handle Edit OLT
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    $oltId = isset($_POST['olt_id']) ? (int) $_POST['olt_id'] : null;
    $oltName = trim($_POST['olt_name'] ?? '');
    $oltIp = trim($_POST['olt_ip'] ?? '');
    $oltPort = trim($_POST['olt_port'] ?? '161');
    $community = trim($_POST['community'] ?? 'public');
    $description = trim($_POST['description'] ?? '');

    if ($oltId && $oltName && $oltIp) {
        $data = [
            'device_name' => $oltName,
            'ip_address' => $oltIp,
            'snmp_port' => (int) $oltPort,
            'read_community' => $community,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($obj->updateData('tbl_olt_devices', $data, ['id' => $oltId])) {
            $obj->notificationStore('OLT Device Updated Successfully', 'success');
            echo '<script>setTimeout(() => { location.reload(); }, 1000);</script>';
        } else {
            $obj->notificationStore('Failed to Update OLT Device', 'error');
        }
    } else {
        $obj->notificationStore('Please fill all required fields', 'warning');
    }
}

// Handle Delete OLT
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $oltId = isset($_POST['olt_id']) ? (int) $_POST['olt_id'] : null;
    if ($oltId) {
        if ($obj->singleDeleteData('tbl_olt_devices', "id = $oltId")) {
            $obj->notificationStore('OLT Device Deleted Successfully', 'success');
            echo '<script>setTimeout(() => { location.reload(); }, 1000);</script>';
        } else {
            $obj->notificationStore('Failed to Delete OLT Device', 'error');
        }
    }
}

// Handle explicit Active / Inactive actions
if (isset($_GET['status_id'], $_GET['status'])) {
    $oltId = (int) $_GET['status_id'];
    $newStatus = $_GET['status'] === 'active' ? 1 : 0;
    $olt = $obj->details_by_cond('tbl_olt_devices', "id = $oltId");
    if ($olt && $obj->updateData('tbl_olt_devices', ['status' => $newStatus], ['id' => $oltId])) {
        $obj->notificationStore('OLT status updated successfully', 'success');
    } else {
        $obj->notificationStore('Failed to update OLT status', 'error');
    }
    echo '<script>window.location="?page=olt_management";</script>';
    exit;
}

// Handle legacy Status Toggle
if (isset($_GET['toggle_id'])) {
    $oltId = (int) $_GET['toggle_id'];
    $olt = $obj->details_by_cond('tbl_olt_devices', "id = $oltId");
    $newStatus = $olt['status'] == 1 ? 0 : 1;
    
    if ($obj->updateData('tbl_olt_devices', ['status' => $newStatus], ['id' => $oltId])) {
        $obj->notificationStore('OLT Status Updated', 'success');
        echo '<script>window.location="?page=olt_management";</script>';
    }
}
?>

<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="text-md text-neutral-500">OLT Device Management</h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOltModal">
                <i class="ri-add-line"></i> Add OLT Device
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>SL</th>
                            <th>OLT Name</th>
                            <th>IP Address:Port</th>
                            <th>Community</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($oltDevices)): ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($oltDevices as $olt): ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($olt['device_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($olt['ip_address']) . ':' . htmlspecialchars($olt['snmp_port']); ?></td>
                                    <td><?php echo htmlspecialchars($olt['read_community']); ?></td>
                                    <td><?php echo htmlspecialchars('-'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $olt['status'] == 1 ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $olt['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="editOlt(<?php echo htmlspecialchars(json_encode($olt)); ?>)" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <a href="?page=olt_management&status_id=<?php echo $olt['id']; ?>&status=active"
                                           class="btn btn-sm btn-success <?php echo $olt['status'] == 1 ? 'disabled' : ''; ?>" title="Active">Active</a>
                                        <a href="?page=olt_management&status_id=<?php echo $olt['id']; ?>&status=inactive"
                                           class="btn btn-sm btn-secondary <?php echo $olt['status'] == 0 ? 'disabled' : ''; ?>" title="Inactive">Inactive</a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteOlt(<?php echo $olt['id']; ?>)" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No OLT devices found. <a href="#" data-bs-toggle="modal" data-bs-target="#addOltModal">Add one now</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit OLT Modal -->
<div class="modal fade" id="addOltModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add OLT Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="oltForm">
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="olt_id" id="olt_id">

                    <div class="mb-3">
                        <label class="form-label">OLT Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="olt_name" id="olt_name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">OLT IP Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="olt_ip" id="olt_ip" 
                                       placeholder="103.103.33.114" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Port</label>
                                <input type="number" class="form-control" name="olt_port" id="olt_port" value="161">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SNMP Community <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="community" id="community" 
                               value="public" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save OLT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $obj->start_script(); ?>
<script>
function editOlt(olt) {
    document.getElementById('formAction').value = 'edit';
    document.getElementById('olt_id').value = olt.id;
    document.getElementById('olt_name').value = olt.device_name;
    document.getElementById('olt_ip').value = olt.ip_address;
    document.getElementById('olt_port').value = olt.snmp_port;
    document.getElementById('community').value = olt.read_community;
    document.getElementById('description').value = '';
    document.getElementById('modalTitle').textContent = 'Edit OLT Device';
    
    const modal = new bootstrap.Modal(document.getElementById('addOltModal'));
    modal.show();
}

function deleteOlt(oltId) {
    if (confirm('Are you sure you want to delete this OLT device?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="olt_id" value="${oltId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Reset modal on close
document.getElementById('addOltModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formAction').value = 'add';
    document.getElementById('oltForm').reset();
    document.getElementById('modalTitle').textContent = 'Add OLT Device';
    document.getElementById('olt_id').value = '';
});
</script>
<?php $obj->end_script(); ?>
