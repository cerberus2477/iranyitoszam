<?php
namespace App\Repositories;

class CountyRepository extends BaseRepository
{

    function __construct($host = self::HOST, $user = self::USER, $password = self::PASSWORD, $database = self::DATABASE)
    {
        parent::__construct($host, $user, $password, $database);
        $this->tableName = 'counties';
    }
}


//TODO: felülírni orderby-jal

// public function getAll(): array
// {
//     $query = $this->select();

//     // ". ORDER BY name";
//     return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
// }
