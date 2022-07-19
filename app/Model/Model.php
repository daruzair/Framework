<?php

namespace app\Model;
abstract class Model
{
    protected $table = "";
    protected $allColumn = array();
    public $Success = "false", $Message, $Code, $columnNames = "", $columnValues = "", $updateColumns = '', $deleteColunm = '', $mysql_error, $mysql_error_no;
    protected $result, $i = 0, $currentColumn = 0, $values = array();
    protected $con;  public $json=array();

    public function __construct()
    {
        if (empty($this->table)) {
            $this->table = explode('\\', get_class($this));
            $this->table = end($this->table);
            $this->table = $this->table . 's';
        }
        $this->con = Model::Connection();
        $this->setColoum();
    }

    public static function Connection()
    {
        try {
            $con = new \mysqli(DB_HOST . DB_PORT, DB_USER, DB_PASS, DB_NAME);
            return $con;
        } catch (\mysqli_sql_exception $err) {
            echo "Error In Connecting DataBase" . $err->getMessage();
            die();
        }
    }

    private function setColoum()
    {
        $query = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='" . DB_NAME . "' AND `TABLE_NAME`='$this->table';";
        $result = mysqli_query($this->con, $query);
        $result = mysqli_fetch_all($result);
        foreach ($result as $row) {
            array_push($this->allColumn, $row[0]);
        }
    }

    private function getColoum()
    {
        if (isset($this->allColumn[$this->currentColumn])) {
            $this->currentColumn++;
            return $this->allColumn[$this->currentColumn - 1];
        }
        $this->currentColumn = 0;
        return false;
    }

    public function __get($key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : "";
    }

    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function Json()
    {
        $this->json["Success"]=$this->Success;
        $this->json["Message"]=$this->Message;
        $this->json["Code"]=$this->Code;
        echo json_encode($this->json);
        header('Content-Type: application/json; charset=utf-8');
        //http_response_code($this->Code);
    }

    public function query($query)
    {
        $this->result = mysqli_query($this->con, $query);
        $this->Message= $query;
        return $this->result;
    }

    public function chunk($limit)
    {
        $query = "select * from " . $this->table . " limit $limit";
        $this->result = mysqli_query($this->con, $query);
    }

    public function __call($name_of_function, $arguments)
    {
        if ($name_of_function == 'get') {
            switch (count($arguments)) {
                case 0:
                    $query = "select * from " . $this->table;
                    break;
                case 1:
                    $query = "select * from " . $this->table . " where Id ='$arguments[0]'";
                    break;
                case 2:
                    $query = "select * from " . $this->table . " where $arguments[0] ='$arguments[1]'";
                    break;
            }
            $this->Message= $query;
            $this->result = mysqli_query($this->con, $query);
        }
    }

    public function next()
    {
        if (!empty($this->result)) {
            if (mysqli_num_rows($this->result) > $this->i) {
                $this->values = mysqli_fetch_assoc($this->result);
                $this->i++;
                return true;
            }
        }
        return false;
    }

    public function insertGetId()
    {
        $this->insert();
        return mysqli_insert_id($this->con);
    }

    public function insert()
    {
        $this->columnNames = "(";
        $this->columnValues = "(";
        $columnN = $this->getColoum();
        if ($columnN) {
            while ($columnN) {
                $columnV = $this->{$columnN};
                if ($columnV != "") {
                    $this->columnNames .= "`$columnN`,";
                    if ($columnV != 'NULL' || $columnV != 'null'  ) {
                        $this->columnValues .= is_numeric($columnV) ? (int)($columnV) . "," : "'" . $columnV . "',";
                    } else {
                        $this->columnValues .= 'NULL,';
                    }
                }
                $columnN = $this->getColoum();
            }
            $this->columnNames = trim($this->columnNames, ',');
            $this->columnValues = trim($this->columnValues, ',');
            $this->columnNames .= ")";
            $this->columnValues .= ")";
            $query = "INSERT INTO `$this->table` $this->columnNames VALUES $this->columnValues";
            $inserted = mysqli_query($this->con, $query);
            $this->mysql_error = $this->con->error;
            $this->mysql_error_no = $this->con->errno;
            $this->Message= $query;
            return $inserted;
        } else {
            $this->mysql_error = "Table does't contain any column or The Table ($this->table) does't exist ";
            return false;
        }
    }

    public function update($columns)
    {
        $columnName = $this->getColoum();
        if ($columnName) {
            while ($columnName) {
                $columnValue = $this->{$columnName};
                if ($columnValue != "") {
                    $this->updateColumns .= "`$columnName` = ";
                    if ($columnValue != 'NULL' || $columnValue != 'null') {
                        $this->updateColumns .= is_numeric($columnValue) ? (int)($columnValue) . "," : "'" . $columnValue . "',";
                    } else {
                        $this->updateColumns .= 'NULL,';
                    }
                }
                $columnName = $this->getColoum();
            }
            $this->updateColumns = trim($this->updateColumns, ',');
            $query = "UPDATE `$this->table` SET $this->updateColumns WHERE $columns";
            $updated = mysqli_query($this->con, $query);
            $this->mysql_error = $this->con->error;
            $this->mysql_error_no = $this->con->errno;
            $this->Message= $query;
            return $updated;
        } else {
            $this->mysql_error = "Table does't contain any column or The Table ($this->table) does't exist ";
            return false;
        }

    }

    public function delete($opprater = "AND")
    {
        $columnName = $this->getColoum();
        if ($columnName) {
            while ($columnName) {
                $columnValue = $this->{$columnName};
                if ($columnValue != '') {
                    $this->deleteColunm .= "`$columnName` = ";
                    $this->deleteColunm .= is_numeric($columnValue) ? (int)($columnValue) . " $opprater " : "'" . $columnValue . "' $opprater ";
                }
                $columnName = $this->getColoum();
            }
            $this->deleteColunm = trim($this->deleteColunm, " $opprater ");
            $this->deleteColunm .= ";";
            $query = "DELETE FROM `$this->table` WHERE $this->deleteColunm";
            $deleted = mysqli_query($this->con, $query);
            $this->mysql_error = $this->con->error;
            $this->mysql_error_no = $this->con->errno;
            $this->Message= $query;
            return $deleted;

        } else {
            $this->mysql_error = "Table does't contain any column or The Table ($this->table) does't exist ";
            return false;
        }
    }

}