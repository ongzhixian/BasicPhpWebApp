<?php
namespace PineappleFinance\Services;

abstract class BaseDataService {

    protected $serverName;
    protected $connectionInfo;

    public function __construct() {
        $this->serverName = SQLSERVER;
        $this->connectionInfo = array( "Database"=>PINEAPPLE_DB );
    }

    public function execute($tsql, $params = array()) {
        try {
            $conn = sqlsrv_connect( $this->serverName, $this->connectionInfo);
            if ( $conn === false ) {  
                return [
                    "success" => false,
                    "message" => "Unable to connect to database. " . print_r(sqlsrv_errors(), true)
                ];
            }

            if (!$stmt = sqlsrv_prepare($conn, $tsql, $params)) {
                $feedbackMessage = "Failed to prepare statement.";
                die(print_r(sqlsrv_errors(), true));
            }
            
            if (sqlsrv_execute($stmt)) {  
                return [
                    "success" => true,
                    "message" => "Operation completed successfully."
                ];
            } else {
                if (DEBUG) print_r( sqlsrv_errors(), false );
                return [
                    "success" => false,
                    "message" => "Failed to execute operation.",
                    "details" => print_r( sqlsrv_errors(), true )
                ];
            }
        }
        catch(\Exception $e) {
            return [
                "success" => false,
                "message" => "Failed to execute operation: Ex" . $e->getMessage()
            ];
        }
        finally {
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close( $conn);
        }
    }

    public function query($tsql, $params = array()) {
        
        $result = array();

        try {
            $conn = sqlsrv_connect( $this->serverName, $this->connectionInfo );
            if ( $conn === false ) {
                echo "Unable to connect.</br>";
                die( print_r( sqlsrv_errors(), true ) );
            }

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
        catch(\Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        finally {
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close( $conn);
        }

        return $result;
    }

} // End of BaseDataService class
?>