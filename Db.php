<?php
/*
    //Better MySQLi
    //github.com/rssvxoxo
*/

if (! function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

//namespace rr;

class Db
{
    protected $servername = "";
    protected $username = "";
    protected $password = "";
    protected $dbname = "";
    
    public function select($items, $table, $where) {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $conn->set_charset("utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        if ($items != "*") {
            if (is_array($items)) {
                $items = implode(',', $items);
            }
        }
        $sql = $items;
        if (is_array($where)) {
            $whereStatus = TRUE;
            $iterate = explode(' AND ', $where[0]);
            foreach ($iterate as $k => $v) {
                if (str_ends_with($v, '=?')) {
                    $whereArray[trim($v,'=?')] = is_numeric($where[count($whereArray) + 1]) ? intval($where[count($whereArray) + 1]) : "'" . $where[count($whereArray) + 1] . "'";
                }else if (str_contains($v, '=')) {
                    $whereArray[explode("=",$v)[0]] = is_numeric(explode("=",$v)[1]) ? intval(explode("=",$v)[1]) : "'" . explode("=",$v)[1] . "'";
                }
            }
        }
        $statement = 'SELECT ' . $items . ' FROM ' . $table;
        if ($whereStatus)
            $statement .= ' WHERE ' . urldecode(http_build_query($whereArray,'',' AND '));
            
        $result = $conn->query($statement);
        if ($where == "a") {
            $conn->close();
            die(var_dump($result));
        }
        
        if ($result->num_rows > 0) {
            $i = 1; 
            
            while ($i <= $result->num_rows) {
                $i++;
                $row[] = $result->fetch_assoc();
            }
        } else {
            $row = "";
        }
        
        $conn->close();
        /* == 1 */
        if (count($row) == 1) {
            foreach ($row as $k => $v) {
                $row[0] = $v;
            }
        }
        return $row;
    }   

    public function insert($table, $items) {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $conn->set_charset("utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        foreach ($items as $k => $v) {
            $i++;
            if ($i == count($items)) {
                $left .= $k;
                if (is_numeric($v))
                    $right .= $v;
                else
                    $right .= "'" . $v . "'";
            }else{
                $left .= $k . ', ';
                if (is_numeric($v))
                    $right .= $v . ', ';
                else
                    $right .= "'" . $v . "', ";
                
            }
        }
        $statement = 'INSERT INTO ' . $table . ' (' . $left . ') VALUES (' . $right . ')';
        if ($conn->query($statement) === TRUE) {
            $return = '';
        } else {
            $return = $conn->error;
        }
        return $return;
    }
    
    public function update($table, $items, $where) {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $conn->set_charset("utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (is_array($where)) {
            $whereStatus = TRUE;
            $iterate = explode(' AND ', $where[0]);
            foreach ($iterate as $k => $v) {
                if (str_ends_with($v, '=?')) {
                    $whereArray[trim($v,'=?')] = is_numeric($where[count($whereArray) + 1]) ? intval($where[count($whereArray) + 1]) : "'" . $where[count($whereArray) + 1] . "'";
                }else if (str_contains($v, '=')) {
                    $whereArray[explode("=",$v)[0]] = is_numeric(explode("=",$v)[1]) ? intval(explode("=",$v)[1]) : "'" . explode("=",$v)[1] . "'";
                }
            }
        }
        if (is_array($items)) {
            foreach ($items as $k => $v) {
                $itemsUpdate .= "`" . $k . "` = '" . $v . "',";
            }
        }
        $itemsUpdate = substr($itemsUpdate, 0, -1);
        
        $statement = 'UPDATE ' . $table . ' SET ' . $itemsUpdate;
        if ($whereStatus)
            $statement .= ' WHERE ' . urldecode(http_build_query($whereArray,'',' AND '));
            
        if (mysqli_query($conn, $sql)) {
            $return = '';
        }else{
            $return = $conn->error;
        }
        $conn->close();
        return $return;
    }
    
    
}

?>
