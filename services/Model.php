<?php
require_once(realpath(__DIR__ . '/../services/Database.php'));
require_once(realpath(__DIR__ . '/../services/SMS.php'));
require_once(realpath(__DIR__ . '/../services/Mikrotik.php'));
class Model extends Database
{
    protected $smsService;

    public function __construct()
    {
        parent::__construct();
        $this->smsService = new SMS(); // Create an instance of SMS
    }

    // login
     function view_all_agent_for_excel($table_name, $where_cond)
    {

        $data = array();
        $sql = "SELECT 'BSD' AS Name_of_Operator,
                     agent.agent_type AS Type_of_Clients, 
                     'Khilkhet' AS Distribution_Location_Point,
                     agent.ag_name AS Name_of_Clients,
                     agent.connectiontype AS Type_of_Connection,
                     'Shared' AS Type_of_Connectivity,
                     agent.connection_date AS Activation_Date, 
                     agent.mb AS Bandwidth_Allocation_MB, 
                     agent.ip AS Allowcated_Ip,
                     '' AS House_No,
                     zone.zone_name AS Road_No,
                      agent.ag_office_address AS Area,
                     'Dhaka' AS District,
                     'Uttara' AS Thana,
                     agent.ag_mobile_no AS Client_Phone,
                     agent.ag_email AS Mail,
                     agent.taka AS Selling_BandwidthBDT_Excluding_VAT
                     
                     FROM tbl_agent AS agent  
                    LEFT JOIN tbl_zone AS zone ON agent.zone = zone.zone_id WHERE ag_status = 1 order by ag_name ASC";
        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function validation($data)
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function login_check($tableName, $adminUser, $adminPass)
    {

        $sql_login = 'SELECT * FROM ' . $tableName . ' WHERE UserName = :user  AND Password = :pass AND Status = 1';
        $login = $this->connect->prepare($sql_login);
        $login->execute(array(':user' => $adminUser, ':pass' => $adminPass));
        $total = $login->rowCount();

        if ($total == 1) {

            $data = $login->fetch(PDO::FETCH_ASSOC);
            return isset($data) ? $data : NULL;
        } else {
            return false;
        }
    }

    /**
     * Execute a read-only, parameterized SQL statement.
     */
    public function raw_sql($sql, array $params = [])
    {
        $sql = trim($sql);
        if ($sql === '' || !preg_match('/^(SELECT|WITH)\b/i', $sql)) {
            throw new InvalidArgumentException('raw_sql() only accepts read-only SELECT/WITH statements.');
        }
        $q = $this->connect->prepare($sql);
        $q->execute($params);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    // Data Insert Function
    public function insertData($table_name, $form_data)
    {
        try {
            $fields = array_keys($form_data);
            $sql = "INSERT INTO " . $table_name . " (`" . implode('`, `', $fields) . "`) VALUES (:" . implode(', :', $fields) . ")";
            $q = $this->connect->prepare($sql);

            foreach ($form_data as $key => $value) {
                $q->bindValue(':' . $key, $value);
            }

            $q->execute();
            return $this->connect->lastInsertId();
        } catch (PDOException $e) {
            echo "Data insertion failed: " . $e->getMessage();
        }
    }

    // Data Update Function
    public function updateData($table_name, $form_data, $where_conditions)
    {
        try {
            $fields = array_keys($form_data);
            $setClause = implode(' = ?, ', $fields) . ' = ?';
            $where_fields = array_keys($where_conditions);
            $whereClause = implode(' = ? AND ', $where_fields) . ' = ?';

            $sql = "UPDATE $table_name SET $setClause WHERE $whereClause";
            $q = $this->connect->prepare($sql);

            $values = array_values($form_data);
            foreach ($values as $index => $value) {
                $q->bindValue($index + 1, $value);
            }

            $where_values = array_values($where_conditions);
            foreach ($where_values as $index => $value) {
                $q->bindValue(count($values) + $index + 1, $value);
            }

            $q->execute();
            return $q->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Data update failed: " . $e->getMessage();
        }
    }

    // Data Delete Function
    // public function deleteData($table_name, $where_conditions)
    // {
    //     try {
    //         $where_clauses = [];
    //         $values = [];

    //         // Loop through the where_conditions and build the WHERE clause dynamically
    //         foreach ($where_conditions as $condition) {
    //             if (count($condition) === 3) {
    //                 $where_clauses[] = "{$condition[0]} {$condition[1]} ?";
    //                 $values[] = $condition[2];  // Bind the value for the condition
    //             }
    //         }

    //         // Combine all conditions using 'AND'
    //         $whereClause = implode(' AND ', $where_clauses);
    //         $sql = "DELETE FROM $table_name WHERE $whereClause";

    //         // Prepare and execute the query
    //         $q = $this->connect->prepare($sql);

    //         // Bind values
    //         foreach ($values as $index => $value) {
    //             $q->bindValue($index + 1, $value);
    //         }

    //         $q->execute();
    //         return $q->rowCount() > 0; // Return true if rows were deleted

    //     } catch (PDOException $e) {
    //         echo "Data deletion failed: " . $e->getMessage();
    //         return false;
    //     }
    // }
public function singleDeleteData($table_name, $condition)
    {
        throw new LogicException('singleDeleteData() is disabled; use deleteData() with parameterized conditions.');
    }
    public function deleteData($table_name, $where_conditions)
    {
        try {
            if (empty($where_conditions) || !is_array($where_conditions)) {
                throw new Exception("Where conditions must be a non-empty array.");
            }

            $where_clauses = [];
            $values = [];

            // Loop through the where_conditions and build the WHERE clause dynamically
            foreach ($where_conditions as $condition) {
                if (!is_array($condition) || count($condition) !== 3) {
                    throw new Exception("Each where condition must be an array with 3 elements: column, operator, value.");
                }
                $where_clauses[] = "{$condition[0]} {$condition[1]} ?";
                $values[] = $condition[2];  // Bind the value for the condition
            }

            // Combine all conditions using 'AND'
            $whereClause = implode(' AND ', $where_clauses);
            $sql = "DELETE FROM $table_name WHERE $whereClause";

            // Prepare and execute the query
            $q = $this->connect->prepare($sql);

            // Bind values
            foreach ($values as $index => $value) {
                $q->bindValue($index + 1, $value);
            }

            $q->execute();
            return $q->rowCount() > 0; // Return true if rows were deleted

        } catch (PDOException $e) {
            error_log("Data deletion failed: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            throw $e;
        }
    }



    // $conditions = ['where' => ['name', '=', 'asd']];
    // $conditions = ['where' => [['name', '=', 'asd'],['name', '=', 'asd']]];
    // $conditions = ['orwhere' => ['name', '=', 'asd']];
    // $conditions = ['orwhere' => [['name', '=', 'asd'],['name', '=', 'asd']]];
    // $conditions = ['between' => ['created', 'fromdate', 'todate']];

    // Fetch All Data Function with Dynamic WHERE Conditions and ORDER BY
    public function getAllData($table_name, $conditions = [], $column = '*', $joinable = '', $group_by = '', $order_by = '', $order_direction = 'DESC', $limit = null, $offset = null)
    {
        try {
            $sql = "SELECT $column FROM $table_name $joinable";
            $where_clauses = [];
            $orwhere_clauses = [];

            if (!empty($conditions)) {
                foreach ($conditions as $type => $condition_array) {
                    // Ensure we have an array of conditions
                    if (!is_array($condition_array)) {
                        continue;
                    }

                    // Check if it's an array of conditions
                    if ($type === 'where') {
                        if (count($condition_array) === 3) {
                            $where_clauses[] = "$condition_array[0] $condition_array[1] $condition_array[2]";
                        } else {
                            foreach ($condition_array as $condition) {
                                $where_clauses[] = "$condition[0] $condition[1] $condition[2]";
                            }
                        }
                    }

                    if ($type === 'between') {
                        $where_clauses[] = "$condition_array[0] BETWEEN $condition_array[1] AND $condition_array[2]";
                    }

                    if ($type === 'orwhere') {
                        if (count($condition_array) === 3) {
                            $orwhere_clauses[] = "$condition_array[0] $condition_array[1] $condition_array[2]";
                        } else {
                            foreach ($condition_array as $condition) {
                                $orwhere_clauses[] = "$condition[0] $condition[1] $condition[2]";
                            }
                        }
                    }
                }
            }

            // Combine the WHERE clauses using AND logic
            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(' AND ', $where_clauses);
                if (!empty($orwhere_clauses)) {
                    $sql .= " OR (" . implode(' AND ', $orwhere_clauses) . ")";
                }
            }

            // GROUP BY clause
            if (!empty($group_by)) {
                $sql .= " GROUP BY $group_by";
            }

            // ORDER BY clause
            if (!empty($order_by)) {
                $sql .= " ORDER BY $order_by $order_direction";
            }

            // LIMIT and OFFSET
            if (!is_null($limit)) {
                $sql .= " LIMIT $limit";
                if (!is_null($offset)) {
                    $sql .= " OFFSET $offset";
                }
            }

            // Prepare the SQL query
            $q = $this->connect->prepare($sql);

            try {
                $q->execute();
                return $q->fetchAll(PDO::FETCH_ASSOC);
            } catch (\Throwable $th) {
                //throw $th;
                return $th;
                // return false;
            }
        } catch (PDOException $e) {
            echo "Data retrieval failed: " . $e->getMessage();
        }
    }


    // $conditions = ['where' => ['name', '=', 'asd']];
    // $conditions = ['where' => [['name', '=', 'asd'],['name', '=', 'asd']]];
    // $conditions = ['orwhere' => ['name', '=', 'asd']];
    // $conditions = ['orwhere' => [['name', '=', 'asd'],['name', '=', 'asd']]];
    // $conditions = ['between' => ['created', 'fromdate', 'todate']];

    // Fetch Single Row Function with Dynamic WHERE Conditions and ORDER BY
    public function getSingleData($table_name, $conditions = [])
    {
        try {
            $sql = "SELECT * FROM $table_name";

            $where_clauses = [];
            $orwhere_clauses = [];

            if (!empty($conditions)) {
                foreach ($conditions as $type => $condition_array) {
                    // Ensure we have an array of conditions
                    if (!is_array($condition_array)) {
                        continue;
                    }

                    // Check if it's an array of conditions
                    if ($type === 'where') {
                        if (count($condition_array) === 3) {
                            $where_clauses[] = "$condition_array[0] $condition_array[1] $condition_array[2]";
                        } else {
                            foreach ($condition_array as $condition) {
                                $where_clauses[] = "$condition[0] $condition[1] $condition[2]";
                            }
                        }
                    }

                    if ($type === 'between') {
                        $where_clauses[] = "$condition_array[0] BETWEEN $condition_array[1] AND $condition_array[2]";
                    }

                    if ($type === 'orwhere') {
                        if (count($condition_array) === 3) {
                            $orwhere_clauses[] = "$condition_array[0] $condition_array[1] $condition_array[2]";
                        } else {
                            foreach ($condition_array as $condition) {
                                $orwhere_clauses[] = "$condition[0] $condition[1] $condition[2]";
                            }
                        }
                    }
                }
            }

            // Combine the WHERE clauses using AND logic
            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(' AND ', $where_clauses);
                if (!empty($orwhere_clauses)) {
                    $sql .= " OR (" . implode(' AND ', $orwhere_clauses) . ")";
                }
            }

            $q = $this->connect->prepare($sql);
            $q->execute();
            return $q->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Data retrieval failed: " . $e->getMessage();
        }
    }


    // Function to get the total count of rows with dynamic WHERE conditions
    public function totalCount($table_name, $where_conditions = [])
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM $table_name";

            // Handle WHERE conditions if provided
            if (!empty($where_conditions)) {
                $where_clauses = [];
                foreach ($where_conditions as $condition) {
                    // Each condition is a compact array like ['field', 'operator', 'value']
                    if (strtoupper($condition[1]) == 'BETWEEN') {
                        // Special case for BETWEEN, value should be an array with two values
                        $where_clauses[] = "{$condition[0]} BETWEEN ? AND ?";
                    } else {
                        $where_clauses[] = "{$condition[0]} {$condition[1]} ?";
                    }
                }
                $sql .= " WHERE " . implode(' AND ', $where_clauses);
            }

            $q = $this->connect->prepare($sql);

            // Bind WHERE condition values
            if (!empty($where_conditions)) {
                $index = 1;
                foreach ($where_conditions as $condition) {
                    if (strtoupper($condition[1]) == 'BETWEEN') {
                        // Bind two values for BETWEEN
                        $q->bindValue($index++, $condition[2][0]); // First value
                        $q->bindValue($index++, $condition[2][1]); // Second value
                    } else {
                        // Bind the single value for other conditions
                        $q->bindValue($index++, $condition[2]);
                    }
                }
            }

            $q->execute();
            return $q->rowCount();
        } catch (PDOException $e) {
            echo "Count retrieval failed: " . $e->getMessage();
        }
    }

    // View All Data Function
    public function view_all($table_name)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name";
        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // View All Complain Condition wise Function
    public function view_all_complain_by_cond($table_name)
    {
        $data = array();
        $sql = "SELECT comp.*, agnt.ag_name, agnt.ag_mobile_no,agnt.ip, agnt.ag_office_address, emp.employee_name FROM tbl_complains AS comp, tbl_agent AS agnt,tbl_employee AS emp WHERE comp.customer_id = agnt.ag_id AND emp.id = comp.solve_by AND comp.id!=0 AND comp.status != 0 ORDER BY comp.id DESC";

        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // View All Data Condition wise Function
    public function view_all_by_cond($table_name, $where_cond)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name WHERE $where_cond";

        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // Details Data View Condition Wise Function
    public function details_by_cond($table_name, $where_cond)
    {

        $sql = "SELECT * FROM $table_name WHERE $where_cond";
        $q = $this->connect->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


    function Update_data($table_name, $form_data, $where_clause = '')
    {

        $whereSQL = '';
        if (!empty($where_clause)) {

            if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {

                $whereSQL = " WHERE " . $where_clause;
            } else {
                $whereSQL = " " . trim($where_clause);
            }
        }

        $sql = "UPDATE " . $table_name . " SET ";

        $sets = array();
        foreach ($form_data as $column => $value) {
            $sets[] = "`" . $column . "` = '" . $value . "'";
        }
        $sql .= implode(', ', $sets);

        $sql .= $whereSQL;
        $q = $this->connect->prepare($sql);

        return $q->execute() or die(print_r($q->errorInfo()));
    }

    // View All Data Condition wise Function
    public function view_all_by_status($status)
    {
        $data = array();

        $sql = "SELECT comp.*, agnt.ag_name, agnt.ag_mobile_no,agnt.ip,agnt.ag_office_address, emp.employee_name FROM tbl_complains AS comp, tbl_agent AS agnt,tbl_employee AS emp WHERE comp.customer_id = agnt.ag_id AND emp.id = comp.solve_by AND comp.id!=0 AND comp.status = $status ORDER BY comp.id DESC ";
        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    //Get column value by another value
    function getSettingValue($field, $otherParameter = null)
    {
        $sql = "SELECT `value` FROM `tbl_setting` WHERE `field` = :field";

        $conditions = [':field' => $field];

        if (!empty($otherParameter)) {
            $sql .= " AND `other_parameter` = :otherParameter";
            $conditions[':otherParameter'] = $otherParameter;
        }

        $q = $this->connect->prepare($sql);
        $q->execute($conditions);

        $settingData = $q->fetch(PDO::FETCH_ASSOC);

        return $settingData ? $settingData['value'] : null;
    }
    
    function updateSettingValue($field, $newValue, $otherParameter)
{
    $sql = "UPDATE `tbl_setting` 
            SET `value` = :value 
            WHERE `field` = :field AND `other_parameter` = :otherParameter";

    $params = [
        ':value' => $newValue,
        ':field' => $field,
        ':otherParameter' => $otherParameter
    ];

    $q = $this->connect->prepare($sql);
    return $q->execute($params);
}


    //Raw SQL
    // Executes raw SQL and returns the result as an array
    public function rawSql($rawSql)
    {
        $q = $this->connect->prepare($rawSql);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC); // Directly fetch all results
    }

    // Fetch only the first row as an associative array
    public function rawSqlSingle($rawSql)
    {
        $q = $this->connect->prepare($rawSql);
        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }


    // Display session data and remove it after displaying
    public function sessionData($data)
    {
        if (!empty($_SESSION[$data])) {
            echo $_SESSION[$data];
            unset($_SESSION[$data]); // Clear session data after use
        }
    }

    // Example function to get attendance data for a specific employee and date
    function getAttendanceData($employeeId)
    {
        // Example query - adjust based on your database schema
        $sql = "SELECT * FROM tbl_employee WHERE id = :employeeId";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $stmt->bindParam(':attendanceDate', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Store notifications in the session
    public function notificationStore($text, $alert)
    {
        $_SESSION['count'] = 0; // Required for notificationShowRedirect() method

        $icon = ($alert == "success") ? "success" : (($alert == "info") ? "info" : (($alert == "warning") ? "warning" : (($alert == "danger") ? "error" : 'success')));

        $script = '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: "' . htmlspecialchars($icon) . '",
                    title: "' . htmlspecialchars($alert) . '",
                    text: "' . htmlspecialchars($text) . '",
                    timer: 1500,
                    showConfirmButton: false,
                    position: "top-end"
                });
            });
        </script>
    ';

        $notification = $script;

        // Append notification to the session
        $_SESSION['notification'] = ($_SESSION['notification'] ?? '') . $notification;
    }

    public function notificationShowRedirect()
    {

        if (isset($_SESSION['count'])) {
            $_SESSION['count']++;
        } else {
            $_SESSION['count'] = 0;
        }

        if (isset($_SESSION["notification"]) && !empty($_SESSION["notification"])) {
            echo $_SESSION["notification"];

            if ($_SESSION['count'] > 1) {
                unset($_SESSION["notification"]);
            }
        }
    }

    // Show notifications stored in the session
    public function notificationShow()
    {
        $this->sessionData('notification');
    }


    // Check user Menue permission
    public function userMenuePermission($permission)
    {
        if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'SA') {
            return true;
        }

        $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
        if (!$userId) {
            return false;
        }

        $wpdata =  $this->details_by_cond('vw_user_info', "UserId=$userId");
        if ($wpdata && isset($wpdata['MenuPermission']) && !empty($wpdata['MenuPermission'])) {
            $menuPermission = @unserialize($wpdata['MenuPermission']);
            if ($menuPermission !== false && is_array($menuPermission)) {
                if (in_array($permission, $menuPermission)) {
                    return true;
                }
            }
        }

        return false;
    }

    // Check user work permission
    public function userWorkPermission($permission)
    {
        if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'SA') {
            return true;
        }

        $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
        if (!$userId) {
            return false;
        }

        $wpdata = $this->details_by_cond('vw_user_info', "UserId=$userId");
        if ($wpdata && isset($wpdata['WorkPermission']) && !empty($wpdata['WorkPermission'])) {
            $workPermissions = @unserialize($wpdata['WorkPermission']);
            if ($workPermissions !== false && is_array($workPermissions)) {
                if (in_array($permission, $workPermissions)) {
                    return true;
                }
            }
        }
        return false;
    }

    // Helper function to fetch data based on conditions (assumed to exist)
    private function detailsSelectedFieldByCond($table, $fields, $conditions, $params = [])
    {
        $sql = "SELECT $fields FROM $table WHERE $conditions";
        $q = $this->connect->prepare($sql);
        $q->execute($params);
        return $q->fetch(PDO::FETCH_ASSOC);
    }


    public function convertBanglaToEnglishNumbers($input)
    {
        // Array mapping of Bangla numbers to English numbers
        $banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        // Replace Bangla numbers with English numbers
        $output = str_replace($banglaNumbers, $englishNumbers, $input);

        return $output;
    }

    // billpay // paybilldeleted // paybillupdate // discount // previousdue //package ///package---change

    // public function billUpdate($customerId, $tag, $amount,$type='plus',$irequestid='') {
    //     // billing
    //     if($tag == 'billpay') {
    //             update table tbl_agent column  bill_status = 1
    //             insert data into tbl_account (`acc_id`, `cus_id`, `agent_id`, `acc_head`, `acc_amount`, `pay_amount`, `acc_description`, `acc_type`, `entry_by`, `entry_date`, `update_by`, `last_update` FROM `tbl_account`),
    //             update table customer_billing column bame tpaid+$amount  and dueadvance-$amount  and 
    //             cheeck due here (customer_billing  table column dueadvance ) if due  < 0 so update  pay_status = 0, tbl_agent
    //     }elseif($tag == 'paybilldeleted'){    
    //         delete data into tbl_account table (`acc_id`, `cus_id`, `agent_id`, `acc_head`, `acc_amount`, `pay_amount`, `acc_description`, `acc_type`, `entry_by`, `entry_date`, `update_by`, `last_update` FROM `tbl_account`),
    //         update table customer_billing column name tpaid-$amount  and dueadvance+$amount  and 
    //         cheeck due here (customer_billing  table column dueadvance ) if due  < 0 so update  pay_status = 0, tbl_agent
    //     }elseif($tag == 'paybillupdate'){    
    //         update data into tbl_account table (`acc_id`, `cus_id`, `agent_id`, `acc_head`, `acc_amount`, `pay_amount`, `acc_description`, `acc_type`, `entry_by`, `entry_date`, `update_by`, `last_update` FROM `tbl_account`),
    //         update table customer_billing column name tpaid-$amount  and dueadvance+$amount  and 
    //         cheeck due here (customer_billing  table column dueadvance ) if due  < 0 so update  pay_status = 0,  tbl_agent
    //     }
    //     elseif($tag == 'discount'){ 
    //         insert  into table name bonus ( `id`, `ag_id`, `amount`, `description`, `entryby`, `date`, `updated_at` FROM `bonus` ) 
    //         update dueadvance, totaldiscount,  to table  `customer_billing` WHERE agid = $customerId
    //         cheeck due here (customer_billing  table column dueadvance ) if due  < 0 so update  pay_status = 0, tbl_agent

    //     }elseif($tag == 'previousdue'){ 
    //         update table customer_billing  previousdue = $amount and dueadvance-previousdue+$amount  
    //         check due here (customer_billing  table column dueadvance ) if due  < 0 so update  pay_status = 0,

    //    }elseif($tag == 'package'){
    //         update tbl_agent for mb , taka change  
    //         update customer_billing monthlybill =$amount , (totalgenerate,dueadvance (if type == 'increease') +$amount),
    //   }

    // }

    // ===============================================================================================================================================================
    // ============================================================SMS================================================================================================
    // ===============================================================================================================================================================
    // sendsms('8801700000', 'hello world');
    //Send Single SMS
    public function sendsms($phoneNumber, $message)
    {
        $api_key = $this->getSettingValue('sms', 'pass');
        $sender = $this->getSettingValue('sms', 'sender');

        $response = $this->smsService->sendSMS($api_key, $sender, $phoneNumber, $message);

        $responseData = json_decode($response, true);
        return (isset($responseData['response_code']) && $responseData['response_code'] == 202) ? true : $responseData;
    }

    // [["to" => $mobilenum, "message" => $mass],["to" => $mobilenum, "message" => $mass], .......];
    //Send Array SMS
    public function sms_send($smsArray)
    {
        $api_key = $this->getSettingValue('sms', 'pass');
        $sender = $this->getSettingValue('sms', 'sender');
        $response = $this->smsService->sms_send($api_key, $sender, $smsArray);

        $responseData = json_decode($response, true);
        return (isset($responseData['response_code']) && $responseData['response_code'] == 202) ? true : $responseData;
    }

    //=====================================================================================================================================================================
    // ============================================================Mikrotik================================================================================================
    // ====================================================================================================================================================================

    // Mikrotik  mik_port
    public function checkConnection($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    return $mikrotik->connected;
                }
            }
        }
        return false;
    }

    public function viewAllPppSecret($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/ppp/secret/print');
                    $read = $mikrotik->read(false);
                    $allData = $mikrotik->parseResponse($read);
                    if (!empty($allData)) {
                        return $allData;
                    }
                }
            }
        }
        return false;
    }

    public function showSingleSecret($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write("/ppp/secret/print", false);
                    $mikrotik->write("?name=$secretName");
                    $read = $mikrotik->read(false);
                    $singleSecretData = $mikrotik->parseResponse($read);
                    if (!empty($singleSecretData)) {
                        return $singleSecretData[0];
                    }
                }
            }
        }
        return false;
    }

    public function monitorTrafic($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/interface/monitor-traffic', true);
                    $mikrotik->write('=interface=LAN', false);
                    $mikrotik->write('=once=');
                    $read = $mikrotik->read(false);
                    $traficArray = $mikrotik->parseResponse($read);
                    if (!empty($traficArray)) {
                        return $traficArray;
                    }
                }
            }
        }
        return false;
    }

    public function monitorTraficSingle($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/interface/monitor-traffic', false);
                    $mikrotik->write('=interface=' . $secretName, false); // <pppoe-server> && pppoe-client
                    $mikrotik->write('=once=');
                    $read = $mikrotik->read(false);
                    $traficArray = $mikrotik->parseResponse($read);
                    if (!empty($traficArray)) {
                        return $traficArray[0];
                    }
                }
            }
        }
        return false;
    }

    //active secret list
    public function pppeoActiveSecretList($mikrotikId)
    {
        $mk =  $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/ppp/active/print', true);
                    $read = $mikrotik->read(false);
                    $interface = $mikrotik->parseResponse($read);
                    if (!empty($interface)) {
                        return $interface;
                    }
                }
            }
        }
        return false;
    }


    public function checkSecretActive($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write("/ppp/secret/print", false);
                    $mikrotik->write("?name=" . $secretName);
                    $read = $mikrotik->read(false);
                    $singleSecretData = $mikrotik->parseResponse($read);
                    if (!empty($singleSecretData)) {
                        if (isset($singleSecretData[0]['disabled'])) {
                            if ($singleSecretData[0]['disabled'] == 'false') {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }


    public function removeSecretActive($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/interface/pppoe-server/print', false);
                    $mikrotik->write('=.proplist=.id', false);
                    $mikrotik->write('?name=<pppoe-' . $secretName . '>');
                    $dataArray = $mikrotik->read();
                    if (!empty($dataArray)) {
                        if (isset($dataArray[0])) {
                            $mikrotik->write('/interface/pppoe-server/remove', false);
                            $mikrotik->write('=.id=' . $dataArray[0]['.id']);
                            $read = $mikrotik->read(false);
                            return $mikrotik->parseResponse($read);
                        }
                    }
                }
            }
        }
        return false;
    }

    public function interfaceStatus($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/interface/print', true);
                    $read = $mikrotik->read(false);
                    $interface = $mikrotik->parseResponse($read);
                    if (!empty($interface)) {
                        return $interface;
                    }
                }
            }
        }
        return false;
    }
    public function interfaceStatusNew($mikrotikId)
    {
        // Fetch Mikrotik credentials from the database
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);

        if ($mk) {
            // Use RouterosAPI to connect to Mikrotik
            $API = new RouterosAPI();
            if ($API->connect($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"])) {
                // Query the list of interfaces
                $API->write('/interface/print', true);
                $response = $API->read(false);
                $interface = $API->parseResponse($response);

                // Disconnect from the Mikrotik router
                $API->disconnect();

                // Return the interface data
                if (!empty($interface)) {
                    return $interface;
                }
            }
        }

        return false;
    }

    public function interfacesingle($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/interface/getall', false);
                    $mikrotik->write('?name=' . $secretName); //// <pppoe-server> && pppoe-client
                    $interface = $mikrotik->read(true);
                    if (!empty($interface)) {
                        return $interface[0];
                    }
                }
            }
        }
        return false;
    }

    public function profilelist($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        // $mk=  $this->getSingleData('mikrotik_user',['where' =>['id', '=', $mikrotikId]]);
        // $mk=  $this->getSingleData('mikrotik_user',[['where' =>['id', '=', 2]],['owhere' =>['id', '=', $mikrotikId]]]);
        // $mk=  $this->getSingleData('mikrotik_user',[['where' =>['id', '=', $mikrotikId],['status', '=', '1']],['owhere' =>['id', '=', 2]]]);

        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->write('/ppp/profile/print', true);
                    $read = $mikrotik->read(false);
                    $profile = $mikrotik->parseResponse($read);
                    if (!empty($profile)) {
                        return $profile;
                    }
                }
            }
        }
        return false;
    }

    public function enableSingleSecret($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/ppp/secret/enable", array(
                        "numbers" => $secretName,
                    ));
                    return true;
                }
            }
        }
        return false;
    }

    public function disableSingleSecret($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/ppp/secret/disable", array(
                        "numbers" => $secretName,
                    ));
                    $this->removeSecretActive($mikrotikId, $secretName);
                    return true;
                }
            }
        }
        return false;
    }

    public function createNewSecret($mikrotikId, $secretName, $password, $profile, $comment = '')
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/ppp/secret/add", array(
                        "name" => $secretName,
                        "password" => $password,
                        "service" => 'pppoe',
                        "profile" => $profile,
                        "comment" => $comment,
                    ));
                    return true;
                }
            }
        }
        return false;
    }

    public function updateExistingSecret($mikrotikId, $secretName, $password, $profile, $existingUsername, $comment = '')
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    if ($existingUsername != $secretName) {
                        $mikrotik->comm("/ppp/secret/set", array(
                            "numbers" => $existingUsername,
                            "name" => $secretName,
                            "password" => $password,
                            "profile" => $profile,
                            "comment" => $comment,
                        ));
                        $this->removeSecretActive($mikrotikId, $existingUsername);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function updateExistingSecretProfile($mikrotikId, $secretName, $profile)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/ppp/secret/set", array(
                        "numbers" => $secretName,
                        "profile" => $profile,
                    ));
                    return true;
                }
            }
        }
        return false;
    }

    public function removeSecret($mikrotikId, $secretName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/ppp/secret/remove", array(
                        "numbers" => $secretName,
                    ));
                    $this->removeSecretActive($mikrotikId, $secretName);
                    return true;
                }
            }
        }
        return false;
    }

    public function createNewQueue($mikrotikId, $name, $target, $limitAt, $maxLimit, $comment = '')
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/add", [
                        "name" => $name,
                        "target" => $target,
                        "limit-at" => $limitAt,
                        "max-limit" => $maxLimit,
                        "comment" => $comment,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function updateSimpleQueueName($mikrotikId, $newName, $oldName)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/set", [
                        ".id" => $oldName,
                        "name" => $newName,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function updateSimpleQueue($mikrotikId, $queueId, $target, $limitAt, $maxLimit, $name)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/set", [
                        ".id" => $queueId,
                        "target" => $target,
                        "limit-at" => $limitAt,
                        "max-limit" => $maxLimit,
                        "name" => $name,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function updateTLSimpleQueue($mikrotikId, $queueId, $target, $limitAt, $maxLimit)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/set", [
                        ".id" => $queueId,
                        "target" => $target,
                        "limit-at" => $limitAt,
                        "max-limit" => $maxLimit,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function updateEnableDisableSimpleQueue($mikrotikId, $queueId, $limitAt, $maxLimit, $disabled)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/set", [
                        ".id" => $queueId,
                        "limit-at" => $limitAt,
                        "max-limit" => $maxLimit,
                        "disabled" => $disabled ? "true" : "false",
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function enableSimpleQueue($mikrotikId, $queueId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/enable", [
                        ".id" => $queueId,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function disableSimpleQueue($mikrotikId, $queueId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/disable", [
                        ".id" => $queueId,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function removeSimpleQueue($mikrotikId, $queueId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $mikrotik->comm("/queue/simple/remove", [
                        ".id" => $queueId,
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function getSingleQueue($mikrotikId, $queueId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                if ($mikrotik->connected) {
                    $response = $mikrotik->comm("/queue/simple/print", ["?name" => $queueId]);
                    if (!empty($response)) {
                        return $response[0];
                    }
                }
            }
        }
        return false;
    }

    public function getAllQueues($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                $response = $mikrotik->comm("/queue/simple/print");
                if (!empty($response)) {
                    return $response;
                }
            }
        }
        return false;
    }

    public function getArpList($mikrotikId)
    {
        $mk = $this->getSingleData('mikrotik_user', ['where' => [['id', '=', $mikrotikId], ['status', '=', '1']]]);
        if ($mk) {
            $mikrotik = new Mikrotik($mk["mik_ip"], $mk["mik_port"], $mk["mik_username"], $mk["mik_password"]);
            if (!empty($mikrotik)) {
                $response = $mikrotik->comm("/ip/arp/print");
                if (!empty($response)) {
                    return $response;
                }
            }
        }
        return false;
    }



    // ==================================================================================================
    // =======================================SCRIPT Dynamic Laod===========================================================
    // ==================================================================================================
    protected $dynamic_scripts = [];

    // Start capturing script content
    public function start_script()
    {
        ob_start();
    }

    // End capturing and store the script content
    public function end_script()
    {
        $script_content = ob_get_clean();
        $this->dynamic_scripts[] = $script_content;
    }

    // Output all dynamic scripts stored
    public function add_dynamic_scripts()
    {
        if (!empty($this->dynamic_scripts)) {
            foreach ($this->dynamic_scripts as $script) {
                echo $script;
            }
        }
    }

    //Total Bill Amount
    public function get_total_bill_amount()
    {
        $sql = "SELECT SUM(taka) as taka FROM tbl_agent WHERE (MONTH(entry_date) != MONTH(CURRENT_DATE) OR YEAR(entry_date) != YEAR(CURRENT_DATE)) AND ag_status=1";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data['taka'];
    }

    // Data Insert Function
    public function Insert_data($table_name, $form_data)
    {
        $fields = array_keys($form_data);

        $sql = "INSERT INTO " . $table_name . "
           (`" . implode('`,`', $fields) . "`)
           VALUES('" . implode("','", $form_data) . "')";

        $q = $this->connect->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));

        return $this->connect->lastInsertId();
    }

    // Total Data Count
    public function Total_Count($table_name, $where_cond)
    {
        $sql = "SELECT * FROM " . $table_name . " WHERE $where_cond";
        $query = $this->connect->prepare($sql);
        $query->execute();
        return $query->rowCount();
    }

    public function get_all_income_with_condition($dateMonth, $dateYear, $condition = '')
    {

        $sql = "SELECT SUM(acc_amount) as amount FROM tbl_account WHERE MONTH(entry_date)='$dateMonth' AND YEAR(entry_date)='$dateYear' $condition";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    // View All Data for New Customer Condition wise Function
    public function view_new_customer_complain_by_cond($table_name)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name ORDER BY id DESC";
        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }



    // View Condition wise Data for Imminent User
    public function view_new_customer_by_status($status)
    {

        $data = array();
        $sql = "SELECT * FROM tbl_complains_new_user WHERE status = $status ORDER BY id DESC";
        $q = $this->connect->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // check this user's permission by existing in menu permission array
    function hasPermission($userType, $getUrl)
    {
        if (!empty($getUrl)) {

            if ($userType == 'SA') {
                return true;
            } else {
                return boolval(in_array($getUrl, $this->permission));
            }
        }
        return false;
    }

    // sum of all income
    public function get_all_income_till_previous_month($monthDate, $yearDate)
    {

        $sql = "SELECT SUM(amount) as amount FROM vw_all_income WHERE MONTH(entry_date) < '$monthDate' AND YEAR(entry_date)='$yearDate'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function get_all_expense_till_previous_month($monthDate, $yearDate)
    {
        $data = array();
        $sql = "SELECT SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='1' AND MONTH(entry_date) < '$monthDate' AND YEAR(entry_date)='$yearDate'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


    public function get_all_opening_till_previous_month($monthDate, $yearDate)
    {
        $data = array();
        $sql = "SELECT SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='5' AND MONTH(entry_date) < '$monthDate' AND YEAR(entry_date)='$yearDate'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


    // View Selected Field Data Condition wise Function
    public function details_selected_field_by_cond($table_name, $selected_field, $where_cond)
    {
        $sql = "SELECT $selected_field FROM $table_name WHERE $where_cond";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


    //Sum of Expense
    public function get_sum_expense($monthDate, $yearDate)
    {
        $sql = "SELECT SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='1' AND MONTH(entry_date)='$monthDate' AND YEAR(entry_date)='$yearDate'";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


    public function get_dayWise_total_bill_collection($date)
    {

        $sql = "SELECT amount,entry_date FROM vw_bill_collection WHERE entry_date='$date'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['amount'];
        } else {
            return 0;
        }
    }


    public function get_dayWise_total_connection_charge($date)
    {

        $sql = "SELECT SUM(acc_amount) AS total_amount,entry_date FROM tbl_account WHERE acc_type = '4' AND entry_date='$date'";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['total_amount'];
        } else {
            return 0;
        }
    }


    public function get_dayWise_total_other_income($date)
    {

        $sql = "SELECT SUM(amount)AS amount,entry_date FROM vw_others WHERE entry_date='$date'";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['amount'];
        } else {
            return 0;
        }
    }


    public function get_dayWise_total_opening_amount($date)
    {

        $sql = "SELECT entry_date,SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='5' AND entry_date='$date'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['amount'];
        } else {
            return 0;
        }
    }


    public function get_dayWise_total_expense($date)
    {

        $sql = "SELECT entry_date,SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='1' AND entry_date='$date'";

        $q = $this->connect->prepare($sql);
        $q->execute();
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['amount'];
        } else {
            return 0;
        }
    }
     public function Reg_user_cond($table_name, $form_data, $where_cond)
    {
        $fields = array_keys($form_data);

        $sql_login = "SELECT * FROM " . $table_name . " WHERE $where_cond";
        $login = $this->connect->prepare($sql_login);
        $login->execute(array());
        $total = $login->rowCount();

        if ($total == '0') {

            $sql = "INSERT INTO " . $table_name . "  (`" . implode('`,`', $fields) . "`) VALUES('" . implode("','", $form_data) . "')";
            $q = $this->connect->prepare($sql);
            $q->execute() or die(print_r($q->errorInfo()));
            return $this->connect->lastInsertId();
        }
    }

    function get_customer_dues($id)
    {
        // global $obj;
        $details = $this->details_by_cond("vw_agent", "ag_id='$id'");
        extract($details);
        $diffMonth = $details['diff_month'];
        $billAmount = $details['taka'];

        $countBillAmountChange = $this->Total_Count('vw_bill_amount_change', "agent_id = $id");

        if ($countBillAmountChange != 0) {

            $billAmountChangeData = $this->details_by_cond('vw_bill_amount_change', "agent_id = $id ORDER BY `vw_bill_amount_change`.`bill_amount_id` DESC");

            $diffrenceFormBillChangeMonth = $billAmountChangeData['bill_change_diff_month'];

            if ($diffrenceFormBillChangeMonth != 0) {

                $total_due = ($billAmount * ($diffrenceFormBillChangeMonth - 1)) + $billAmountChangeData['dueTillEdit'];
            } else {

                $total_due = $billAmountChangeData['dueTillEdit'];
            }
        } else {

            if ($diffMonth != 0) {

                $total_due = $billAmount * $diffMonth;
            } else {
                $total_due = 0; //$billAmount;
            }
        }

        // $total_due += $details['previous_due_amount'];
        $sql = "SELECT sum(acc_amount) as total_amount FROM `vw_account` WHERE agent_id=$id AND acc_type != 2 AND acc_type != 4 AND acc_type != 5";
        $q = $this->connect->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $total_pay = $data['total_amount'];
        $bonus = 0;
        $inactive_bill = 0;
        foreach ($this->view_all_by_cond("bonus", "ag_id='$id' ") as $customer_bonus) {
            extract($customer_bonus);
            $bonus += $customer_bonus['amount'];
        }
        // Inactive month Bill Count
        // $ag_details = $this->details_by_cond("tbl_agent", where_cond "ag_id='$id'");
        $inactive_bill = $this->details_by_cond("tbl_agent_activity", "agent_id='$id'");

        $dueamount = $total_due - ($total_pay + $bonus + isset($inactive_bill['inactive_ammount']) ? $inactive_bill['inactive_ammount'] : 0);
        // $dueamount = $total_due - ($total_pay + $bonus + isset($inactive_bill)? isset($inactive_bill['inactive_ammount'])?$inactive_bill['inactive_ammount']:0:0);
        return $dueamount;
    }
}
