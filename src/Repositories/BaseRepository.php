<?php

namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB
{
    protected string $tableName;

    // public function create(array $data): ?int
    // {
    //     $sql = "INSERT INTO `%s` (%s) VALUES (%s)";
    //     $fields = '';
    //     $values = '';
    //     // foreach ( )
    //     // {

    //     // }
    // }


    public function select()
    {
        return "SELECT * FROM `{$this->tableName}` ORDER BY id";
    }


    public function selectAll(): array
    {
        $query = $this->select();

        // ". ORDER BY name";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function selectById(int $id): array
    {
        $query = $this->select() . " WHERE id=$id";

        $result = $this->mysqli->query($query)->fetch_assoc();
        if (!$result) {
            $result = [];
        }

        return $result;
    }

    function delete($id)
    {
        return $this->mysqli->query("DELETE FROM `{$this->tableName}` WHERE id=$id");
    }

    //INSERT parancs, új record
    public function create(array $data)
    {
        $sql = "INSERT INTO `{$this->tableName}` (%s) VALUES (%s)";
        $fields = '';
        $values = '';
        foreach ($data as $field => $value) {
            if ($fields > '') { //ha nem üres
                $fields .= ',' . $field;
            } else {
                $fields .= $field;
            }

            if ($values > '') {
                $values .= ',' . "'$value'";
            } else {
                $values .= "'$value'";
            }

            $sql = sprintf($sql, $fields, $values);
            $this->mysqli->query($sql);

            //LAST_INSERT_ID() mysql parancs, am iautoincrementes idnál visszaadja a legújjabb id-vel rendelkező rekordot
            $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();

            return $lastInserted['id'];
        }
    }

    public function update(int $id, array $data)
    {
        $sql = "UPDATE `{$this->tableName}` WHERE id = $id SET %s;";
        $fieldsvalues = '';
        foreach ($data as $field => $value) {
            if ($fieldsvalues > '') { //ha nem üres
                $fieldsvalues .= ", $field = '$value'";
            } else {
                $fieldsvalues .= "$field = '$value'";
            }
        }

        $sql = sprintf($sql, $fieldsvalues);
        $this->mysqli->query($sql);

        return $this->selectById($id);
    }

    public function find(int $id)
    {

    }


}



// public function countAll()
// {

// }

// public function getCount()
// {

// }




