<?php
namespace PineappleFinance\Services;

abstract class BaseDataService {
    
    protected $serverName;
    protected $connectionInfo;

    public function __construct() {
        $this->serverName = SQLSERVER;
        $this->connectionInfo = ["Database" => PINEAPPLE_DB];
    }

    private function getConnection() {
        $conn = sqlsrv_connect($this->serverName, $this->connectionInfo);
        if ($conn === false) {
            throw new \RuntimeException("Database connection failed: " . print_r(sqlsrv_errors(), true));
        }
        return $conn;
    }

    public function execute($tsql, $params = []) {
        $conn = null;
        $stmt = null;
        try {
            $conn = $this->getConnection();
            $stmt = sqlsrv_prepare($conn, $tsql, $params);
            
            if (!$stmt) {
                throw new \RuntimeException("Failed to prepare statement: " . print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_execute($stmt)) {
                return ["success" => true, "message" => "Operation successful."];
            }
            
            return [
                "success" => false,
                "message" => "Execution failed.",
                "details" => sqlsrv_errors()
            ];
        } finally {
            if ($stmt) sqlsrv_free_stmt($stmt);
            if ($conn) sqlsrv_close($conn);
        }
    }

    public function query($tsql, $params = []) {
        $conn = null;
        $stmt = null;
        $result = [];

        try {
            $conn = $this->getConnection();
            $stmt = sqlsrv_query($conn, $tsql, $params);

            if ($stmt === false) {
                throw new \RuntimeException("Query execution failed: " . print_r(sqlsrv_errors(), true));
            }

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $result[] = $row;
            }
            return $result;
        } finally {
            if ($stmt) sqlsrv_free_stmt($stmt);
            if ($conn) sqlsrv_close($conn);
        }
    }
} // End of BaseDataService class
?>