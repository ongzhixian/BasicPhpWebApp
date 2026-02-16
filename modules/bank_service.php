<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IBankService {

    public function GetBankList($user_id);

    public function GetBank($code);

    public function GetBankIdNamePairList();

    public function RegisterBank($code, $full_name);

    public function UpdateBank($code, $full_name);
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

    public function GetBankIdNamePairList() {
        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB);
        
        $result = array();

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo);
            if ( $conn === false ) {
                echo "Unable to connect.</br>";
                die( print_r( sqlsrv_errors(), true));
            }  

            $tsql = "select * from bank order by name;";
            $params = array();

            $stmt = sqlsrv_query( $conn, $tsql, $params);
            if( $stmt === false ) {
                echo "Error in executing query.</br>";
                die( print_r( sqlsrv_errors(), true));
            }
            
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $result[] = $row;
            }
            
            return $result;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }

        return $result;
    }

    public function RegisterBank($code, $full_name) {
        $tsql = <<<EOT
        insert into bank (name, full_name) values (?, ?);
        EOT;
        $params = array($code, $full_name);
        return $this->execute($tsql, $params);
    }

    public function UpdateBank($code, $full_name) {
        $tsql = <<<EOT
        update bank 
        set full_name = ?
        , update_at = CURRENT_TIMESTAMP
        where name = ?;
        EOT;

        $params = array(&$full_name, &$code);
        return $this->execute($tsql, $params);
    }
}

?>