<?php
class hosxpdata
{
    const db_host = '10.99.100.3';
    const db_name = 'hos';
    const db_user = 'rb';
    const db_password = 'rb2009';

    private $pdo = null;

    public function __construct()
    {
        $conStr = sprintf("mysql:host=%s;dbname=%s;charset=UTF8", self::db_host, self::db_name);
        try {
            $this->pdo = new PDO($conStr, self::db_user, self::db_password);
            $this->pdo->exec("SET NAMES UTF8 ");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function selectAll($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function selectOne($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function insertData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function insertDataLastId($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function updateData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }


    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->pdo = null;
    }
}

/*$mysql = new pdomysql();
$sql = "select * from personnal";
$rows = $mysql->selectAll($sql);
print_r($rows);
echo "<br>";
echo $rows['Name'].' '.$rows['Lastname'];*/
?>
