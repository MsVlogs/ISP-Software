<?php
// Include database connection or model
require(realpath(__DIR__ . '/../../services/Model.php'));
$obj = new Model();  // Assuming Model handles DB connections and queries

header('Content-Type: application/json');
$input = filter_input_array(INPUT_POST);

$response = ['success' => false, 'status' => 'Unknown error.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Case 1: Update data (ensure required fields are present)
    if (isset($_POST['mkid'], $_POST['mik_username'], $_POST['mik_password'], $_POST['mik_ip'], $_POST['mik_port'])) {
        $ismikrotik = $obj->details_by_cond('mikrotik_user', "mik_username='" . $_POST['mik_username'] . "' AND mik_ip='" . $_POST['mik_ip'] . "' AND mik_port='" . $_POST['mik_port'] . "'");
        if ($ismikrotik > 0) {
            $response = [
                'status' => 'User already exists.',
                'success' => false,
            ];
            echo json_encode($response);
            exit;
        }
        $updateResult = $obj->updateData(
            'mikrotik_user',
            [
                'mik_username' => $_POST['mik_username'],
                'mik_password' => $_POST['mik_password'],
                'mik_ip' => $_POST['mik_ip'],
                'mik_port' => $_POST['mik_port']
            ],
            ['id' => $_POST['mkid']]
        );

        if ($updateResult) {
            $response = [
                'status' => 'Data updated successfully!',
                'success' => true,
            ];
        } else {
            $response = [
                'status' => 'Failed to update data.',
                'success' => false,
            ];
        }
    }

    // Case 2: Insert new data (if the required fields are present)
    elseif (isset($_POST['username'], $_POST['password'], $_POST['ip'], $_POST['port'])) {

        $ismikrotik = $obj->details_by_cond('mikrotik_user', "mik_username='" . $_POST['username'] . "' AND mik_ip='" . $_POST['ip'] . "' AND mik_port='" . $_POST['port'] . "'");
        if ($ismikrotik > 0) {
            $response = [
                'status' => 'User already exists.',
                'success' => false,
            ];
            echo json_encode($response);
            exit;
        }
        $insertResult = $obj->insertData(
            'mikrotik_user',
            [
                'mik_username' => $_POST['username'],
                'mik_password' => $_POST['password'],
                'mik_ip' => $_POST['ip'],
                'mik_port' => $_POST['port']
            ]
        );

        if ($insertResult) {
            $response = [
                'status' => 'Your Mikrotik has been added successfully!',
                'success' => true,
                'id' => $insertResult,
            ];
        } else {
            $response = [
                'status' => 'Failed to insert data.',
                'success' => false,
            ];
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mikrotik_id'])) {
        $mikrotik_id = $_POST['mikrotik_id'];
        $updateResult = $obj->updateData('mikrotik_user', ['status' => 0], ['id' => $mikrotik_id]);
        $response = [
            'status' => 'Data Desconnection successfully!',
            'success' => true,
        ];
    }
    // If necessary fields are missing for POST request
    else {
        $response = [
            'status' => 'Missing required data.',
            'success' => false,
        ];
    }
}

// Case 3: Retrieve single data using GET method (for fetching data to populate the modal)
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mkid'])) {
    $id = filter_input(INPUT_GET, 'mkid', FILTER_VALIDATE_INT);
    $id = ($id !== false && $id !== null && $id > 0) ? $id : 0;
    $user = $obj->getSingleData('mikrotik_user', ['id' => $id]);
    $user = $obj->details_by_cond("mikrotik_user", "id='$id'");

    if ($user) {
        $response = [
            'id' => $id,
            'mik_username' => $user['mik_username'],
            'mik_ip' => $user['mik_ip'],
            'mik_port' => $user['mik_port'],
            'success' => true
        ];
    } else {
        $response = [
            'status' => 'No user found with the given ID.',
            'success' => false
        ];
    }
}

// Return the response as JSON
echo json_encode($response);
