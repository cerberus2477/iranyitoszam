<?php
namespace App\Repositories;

class CountyRepository extends BaseRepository
{

    function __construct($host = self::HOST, $user = self::USER, $password = self::PASSWORD, $database = self::DATABASE)
    {
        parent::__construct($host, $user, $password, $database);
        $this->tableName = 'counties';
    }

    function delete($id)
    {
        return $this->mysqli->query("DELETE FROM counties WHERE id=$id");
    }
}