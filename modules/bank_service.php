<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IBankService {

    public function GetBankList($user_id);
    public function GetBank($code);
    public function GetBankIdNamePairList($user_id);

    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterBank($data);
    public function UpdateBank($data);
}

class BankService extends BaseDataService implements IBankService {
    
    public function GetBankList($user_id) {
        $tsql = <<<EOT
        select * from bank where create_by = ? order by name;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetBank($code) {

        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB);
        
        $result = array();

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo);  
            if ( $conn === false )  
            {  
                echo "Unable to connect.</br>";  
                die( print_r( sqlsrv_errors(), true));  
            }  

            $tsql = "select * from bank where name = ?;";
            $params = array(&$code);

            $stmt = sqlsrv_query( $conn, $tsql, $params);  
            if( $stmt === false )  
            {  
                echo "Error in executing query.</br>";  
                die( print_r( sqlsrv_errors(), true));  
            }  

            /* Retrieve and display the results of the query. */  
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $result[] = $row; // append to result array
            }
            
            return $result;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            /* Free statement and connection resources. */
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }

        return $result;
    }

    public function GetBankIdNamePairList($user_id) {
        $tsql = <<<EOT
        select * from bank where create_by = ? order by name;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetSanitisedInput($data, $forNew = false) {

        if (empty($data->bank_code)) return null;
        if (empty($data->full_name)) return null;
        if (empty($data->session_user_id)) return null;

        $timestamp = date("Y-m-d H:i:s");
        $session_user_id = trim($data->session_user_id);

        $input = new \stdClass();
        $input->bank_code = trim($data->bank_code);
        $input->full_name = trim($data->full_name);
        $input->update_by = $session_user_id;
        $input->update_at = $timestamp;

        if ( $forNew ) {
            $input->create_by = $session_user_id;
            $input->create_at = $timestamp;
        }
        
        return $input;
    }

    public function RegisterBank($data) {
        $tsql = <<<EOT
        insert into bank (name, full_name, create_by, create_at, update_by, update_at) values (?, ?, ?, ?, ?, ?);
        EOT;
        $params = array($data->bank_code, $data->full_name, $data->create_by, $data->create_at, $data->update_by, $data->update_at);
        return $this->execute($tsql, $params);
    }

    public function UpdateBank($data) {
        $tsql = <<<EOT
        update bank 
        set full_name = ?
        , update_by = ?
        , update_at = ?
        where name = ?;
        EOT;

        $params = array(&$data->full_name, &$data->update_by, &$data->update_at, &$data->bank_code);
        return $this->execute($tsql, $params);
    }
}

?>