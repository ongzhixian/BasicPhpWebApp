<?php
require_once "../_config.php";

class Bank
{

    public function GetList() {

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

            /* Query SQL Server for the login of the user accessing the database. */  
            $tsql = "select * from bank order by name;";


            $stmt = sqlsrv_query( $conn, $tsql);  
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

    public function RegisterBank($code, $full_name) {

        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB);

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo);  
            if ( $conn === false )  
            {  
                echo "Unable to connect.</br>";  
                die( print_r( sqlsrv_errors(), true));  
            }  

            $tsql = "insert into bank (name, full_name) values (?, ?);";
            $params = array(&$code, &$full_name);

            if (!$stmt = sqlsrv_prepare($conn, $tsql, $params)) {
                $feedbackMessage = "Failed to prepare statement.";
                die(print_r(sqlsrv_errors(), true));  
            }
            
            if (sqlsrv_execute($stmt)) {  
                $feedbackMessage = "Bank added successfully.";
            } else {
                $feedbackMessage = "Failed to add bank.";
                die(print_r(sqlsrv_errors(), true));  
            }  
            
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            /* Free statement and connection resources. */
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }
    }

    public function UpdateBank($code, $full_name) {

        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB);

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo);  
            if ( $conn === false )  
            {  
                echo "Unable to connect.</br>";  
                die( print_r( sqlsrv_errors(), true));  
            }  

            // $tsql = "update bank 
            // set full_name = ?
            // , update_at = CURRENT_TIMESTAMP
            // where name = ?;";

            $tsql = <<<EOT
            update bank 
            set full_name = ?
            , update_at = CURRENT_TIMESTAMP
            where name = ?;
            EOT;

            $params = array(&$full_name, &$code);

            if (!$stmt = sqlsrv_prepare($conn, $tsql, $params)) {
                $feedbackMessage = "Failed to prepare statement.";
                die(print_r(sqlsrv_errors(), true));  
            }
            
            if (sqlsrv_execute($stmt)) {  
                $feedbackMessage = "Bank updated successfully.";
            } else {
                $feedbackMessage = "Failed to update bank.";
                die(print_r(sqlsrv_errors(), true));  
            }  
            
            return true;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            /* Free statement and connection resources. */
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }

        return false;
    }

    // Bank Account functions

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

    public function GetBankAccountList() {
        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB );
        
        $result = array();

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo );
            if ( $conn === false ) {
                echo "Unable to connect.</br>";
                die( print_r( sqlsrv_errors(), true ) );
            }

            $tsql = <<<EOT
            select	ba.id
                    , b.name
                    , ba.account_code
                    , ba.description
                    , ba.balance
                    , ba.update_at
            from	bank_account ba
            inner join bank b on ba.bank_id = b.id
            order by b.name;
            EOT;

            $params = array();

            $stmt = sqlsrv_query( $conn, $tsql, $params );
            if( $stmt === false ) {
                echo "Error in executing query.</br>";
                die( print_r( sqlsrv_errors(), true ) );
            }
            
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
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

    public function RegisterBankAccount($data) {
        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB);

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo);  
            if ( $conn === false )  
            {  
                echo "Unable to connect.</br>";  
                die( print_r( sqlsrv_errors(), true));  
            }  

            $tsql = "insert into bank_account (bank_id, account_code, description, balance) values (?, ?, ?, ?);";
            $params = array(&$data->bank_code, &$data->account_number, &$data->account_description, &$data->account_balance);

            if (!$stmt = sqlsrv_prepare($conn, $tsql, $params)) {
                $feedbackMessage = "Failed to prepare statement.";
                die(print_r(sqlsrv_errors(), true));  
            }
            
            if (sqlsrv_execute($stmt)) {  
                $feedbackMessage = "Bank added successfully.";
            } else {
                $feedbackMessage = "Failed to add bank.";
                die(print_r(sqlsrv_errors(), true));  
            }  
            
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            /* Free statement and connection resources. */
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }
    }

    public function GetBankAccount($accountCode = null) {
        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB );
        
        $result = array();

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo );
            if ( $conn === false ) {
                echo "Unable to connect.</br>";
                die( print_r( sqlsrv_errors(), true ) );
            }

            $tsql = <<<EOT
            select	ba.id
                    , b.name
                    , ba.account_code
                    , ba.description
                    , ba.balance
                    , ba.update_at
            from	bank_account ba
            inner join bank b on ba.bank_id = b.id
            where ba.account_code = ?
            order by b.name;
            EOT;

            $params = array($accountCode);
            $stmt = sqlsrv_query( $conn, $tsql, $params );
            if( $stmt === false ) {
                echo "Error in executing query.</br>";
                die( print_r( sqlsrv_errors(), true ) );
            }
            
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
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

    public function UpdateBankAccount($data) {
        $serverName = SQLSERVER;
        $connectionInfo = array( "Database"=>PINEAPPLE_DB );

        try {
            $conn = sqlsrv_connect( $serverName, $connectionInfo );  
            if ( $conn === false )  
            {  
                echo "Unable to connect.</br>";  
                die( print_r( sqlsrv_errors(), true ) );  
            }  

            $tsql = <<<EOT
            update ba
            set ba.description = ?
            , ba.balance = ?
            , ba.update_at = CURRENT_TIMESTAMP
            from bank_account ba
            where ba.account_code = ?;
            EOT;

            $params = array(&$data->account_description, &$data->account_balance, &$data->account_number);

            if (!$stmt = sqlsrv_prepare($conn, $tsql, $params)) {
                $feedbackMessage = "Failed to prepare statement.";
                die(print_r(sqlsrv_errors(), true));  
            }
            
            if (sqlsrv_execute($stmt)) {  
                $feedbackMessage = "Bank account updated successfully.";
            } else {
                $feedbackMessage = "Failed to update bank account.";
                die(print_r(sqlsrv_errors(), true));  
            }  
            
            return true;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            /* Free statement and connection resources. */
            sqlsrv_free_stmt( $stmt);  
            sqlsrv_close( $conn);
        }

        return false;
    }

    // Sanitised value input

    public function sanitiseForUpdate($data) {
        if (empty($data->account_number)) return null;
        if (!is_numeric($data->account_balance)) return null;
        
        $input = new stdClass();
        $input->account_number = trim($data->account_number);
        $input->account_description = trim($data->account_description);
        $input->account_balance = trim($data->account_balance);

        return $input;
    }
}
?>