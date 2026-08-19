<?php
session_start();
require(realpath(__DIR__ . '/../../services/Model.php'));
$obj = new Model();

// Check if 'mkid' is provided in the GET request
if (isset($_GET['mkid'])) {
    $mikrotik_id = $_GET['mkid'];
    $mikrotikConnection =  $obj->checkConnection($mikrotik_id);
    if ($mikrotikConnection) {
        $response = [
            'status' => 'Connected',
            'connection' => true,
        ];
    } else {
        $updateResult = $obj->updateData('mikrotik_user', ['status' => 0], ['id' => $mikrotik_id]);
        $response = [
            'status' => 'Failed to connect',
            'connection' => false,
        ];
    }
} elseif (isset($_GET['mkidsecretall'])) {

    $mikrotikConnection =  $obj->viewAllPppSecret($_GET['mkidsecretall']);
    if ($mikrotikConnection) {
        $singleMikrotikAgent = $obj->getAllData('tbl_agent', ['where' => ['mikrotik_id', '=', $_GET['mkidsecretall']]]);
        $agentList = [];
        if (!empty($singleMikrotikAgent)) {
            foreach ($singleMikrotikAgent as $agent) {
                $agentList[$agent['ip']] = $agent['ip'];
            }
        }

        $table = '';
        $sl = 1;
        foreach ($mikrotikConnection as $secret) {
            if (@$agentList[$secret['name']] != $secret['name']) {
                continue;
            }

            $lastLogout = (isset($secret['last-logged-out']) && ($secret['last-logged-out'] != 'jan/01/1970 00:00:00')) ? ucfirst($secret['last-logged-out']) : '';
            $statusbtn = ($secret['disabled'] == 'false') ? '<button id="secretCangeStatus" data-status="1" data-name="' . $secret['name'] . '" class="btn btn-xs btn-success">Enable</button>' : '<button id="secretCangeStatus"  data-status="0" data-name="' . $secret['name'] . '" class="btn btn-xs btn-danger">Disable</button>';

            $table .= '
            <tr>
            <td> ' . $sl++ . '</td>
            <td> ' . $secret['name'] . '</td>
            <td> ' . $secret['password'] . '</td>
            <td> ' . $secret['profile'] . '</td>
            <td> ' . $lastLogout . '</td>
            <td> ' . $statusbtn . '</td>
            </tr>';
        }

        $response = [
            'status' =>  $table,
            'connection' => true,
        ];
    } else {
        $response = [
            'status' =>  'No data Found',
            'connection' => false,
        ];
    }
} elseif (isset($_GET['mkidsecretonline'])) {

    $mikrotikConnection =  $obj->pppeoActiveSecretList($_GET['mkidsecretonline']);
    if ($mikrotikConnection) {
        $singleMikrotikAgent = $obj->getAllData('tbl_agent', ['where' => ['mikrotik_id', '=', $_GET['mkidsecretonline'], 'deleted_at', '=', 'NULL']]);
        $agentList = [];
        if (!empty($singleMikrotikAgent)) {
            foreach ($singleMikrotikAgent as $agent) {
                $agentList[$agent['ip']] = $agent['ip'];
            }
        }

        $table = '';
        $sl = 1;
        foreach ($mikrotikConnection as $secret) {
            if (@$agentList[$secret['name']] != $secret['name']) {
                continue;
            }
            $status =  (@$secret['radius'] == 'false') ? 'Running' : 'Stop';
            $table .= '
            <tr>
            <td> ' . $sl++ . '</td>
            <td> ' . $secret['name'] . '</td>
            <td> ' . $secret['caller-id'] . '</td>
            <td> ' . $secret['address'] . '</td>
            <td> ' . $secret['uptime'] . '</td>
            <td>' . $status . ' </td>
            </tr>';
        }

        $response = [
            'status' =>  $table,
            'connection' => true,
        ];
    } else {
        $response = [
            'status' =>  'No data Found',
            'connection' => false,
        ];
    }
} elseif (isset($_GET['mkidsecretunmatching'])) {

    $mikrotikConnection =  $obj->viewAllPppSecret($_GET['mkidsecretunmatching']);
    if ($mikrotikConnection) {
        $singleMikrotikAgent = $obj->getAllData('tbl_agent', ['where' => ['mikrotik_id', '=', $_GET['mkidsecretunmatching']]]);
        $agentList = [];
        $unmatchingAgentList = [];
        if (!empty($singleMikrotikAgent)) {
            foreach ($singleMikrotikAgent as $agent) {
                $agentIP = $agent['ip'] ?? ''; // Avoid undefined index issue
                $agentList[$agentIP] = $agentIP;
            }
        }
        if (!empty($mikrotikConnection)) {
            foreach ($mikrotikConnection as $secret) {
                $unmatchingAgentList[$secret['name']] = $secret['name'];
            }
        }


        $table = '';
        $sl = 1;
        $unmatchingTable = '';
        if (!empty($singleMikrotikAgent)) {
            $i = 1;
            foreach ($singleMikrotikAgent as $agent) {
                if (@$unmatchingAgentList[$agent['ip']] == $agent['ip']) {
                    continue;
                }
                $status2 =  (@$agent['ag_status'] == 1) ? 'Active' : 'Inactive';
                $delete = '<button data-id="' . $agent['ag_id'] . '" class="btn btn-xs btn-danger secretDelete"><iconify-icon icon="mdi:delete" class="text-xl"></iconify-icon></button>';
                $unmatchingTable .= '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . $agent['ag_name'] . '</td>
                    <td>' . $agent['ip'] . '</td>
                    <td>' . $agent['queue_password'] . '</td>
                    <td>' . $agent['mb'] . '</td>
                    <td>' . $status2 . '</td>
                    <td>' . $delete . '</td>
                </tr>';
                $i++;
            }
        }

        foreach ($mikrotikConnection as $secret) {
            if (@$agentList[$secret['name']] == $secret['name']) {
                continue;
            }

            $status =  (@$secret['ag_status'] == 'false') ? 'Active' : 'Inactive';
            $lastLogout = (isset($secret['last-logged-out']) && ($secret['last-logged-out'] != 'jan/01/1970 00:00:00')) ? ucfirst($secret['last-logged-out']) : '';
            $status = ($secret['disabled'] == 'false') ? 'Enable' : 'Disable';
            $actionbtn = '<button  data-status="' . $secret['disabled'] . '"   data-profile="' . $secret['profile'] . '"  data-password="" data-name="' . $secret['name'] . '" data-mkid="' . $_GET['mkidsecretunmatching'] . '" class="btn btn-xs btn-primary secretAddSoft"><iconify-icon icon="mdi:plus" class="text-xl"></iconify-icon></button>';
            $table .= '
            <tr>
            <td> ' . $sl++ . '</td>
            <td> ' . $secret['name'] . '</td>
            <td> ' . $secret['password'] . '</td>
            <td> ' . $secret['profile'] . '</td>
            <td> ' . $secret['service'] . '</td>
            <td> ' . $lastLogout . ' </td>
            <td> ' . $status . ' </td>
            <td> ' . $actionbtn . ' </td>
            </tr>';
        }

        $response = [
            'status' =>  $table,
            'unmatching' =>  $unmatchingTable,
            'connection' => true,
        ];
    } else {
        $response = [
            'status' =>  'No data Found',
            'connection' => false,
        ];
    }
} else if ($_POST['mkid']) {
    $mikrotik_id = $_POST['mkid'];
    $updateResult = $obj->updateData('mikrotik_user', ['status' => 1], ['id' => $mikrotik_id]);
    $response = [
        'status' => 'Data updated successfully!',
        'success' => true,
    ];
} else {
    $response = [
        'status' => 'Invalid ID provided',
        'connection' => false,
    ];
}
echo json_encode($response);
