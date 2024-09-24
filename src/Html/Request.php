<?php

namespace App\Html;

use App\Repositories\Repository;
class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "POST":
                self::postRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    public static function getRequest()
    {
        $uri = self::getResourceName();
        $repo = new Repository($uri);
        $id = self::getResourceId();

        if ($id) {
            $entities = $repo->selectById($id);
        } else {
            $entities = $repo->selectAll();
        }

        $code = 200;
        if (empty($entities)) {
            $code = 404;
            // $message = "$uri not found";
        }
        Response::response($entities, $code);
    }

    private static function deleteRequest()
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
        }
        $resourceName = self::getResourceName();
        $db = new Repository($resourceName);
        $result = $db->delete($id);
        if ($result) {
            Response::response([], 200);
        } else {
            Response::response([], 404);
        }
    }


    //TODO: most lehet pl olyat hogy POST 
    private static function postRequest()
    {

        $resourceName = self::getResourceName();
        $data = self::getRequestData();
        $code = 400;     // Initializing the response code as bad request
        $db = new Repository($resourceName);
        $columnNames = $db->getColumnNames();

        // Checking if all column names exist in the data
        $allColumnsPresent = !array_diff($columnNames, array_keys($data));
        if ($allColumnsPresent) {
            $newId = $db->insert($data);
            if ($newId) {
                $code = 201;
            }
        }

        // Respond with the data and appropriate status code
        Response::response($data, $code);
    }


    //POSTMAN ERROR
    private static function putRequest()
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
        } else {

            $resourceName = self::getResourceName();
            $data = self::getRequestData();
            $code = 400;     // Initializing the response code as bad request
            $db = new Repository($resourceName);
            $columnNames = $db->getColumnNames();

            // Checking if all column names exist in the data
            $allColumnsPresent = !array_diff($columnNames, array_keys($data));
            if ($allColumnsPresent) {
                $result = $db->update($id, $data);
                $code = $result ? 200 : 404;
            }

            // Respond with the data and appropriate status code
            Response::response($data, $code);
        }

    }

    //szétdarabolja és ha az utolsó szám akkor az utolsó előttit adja vissza
    private static function getResourceName()
    {
        $arrUri = self::getResources();
        $last = $arrUri[count($arrUri) - 1]; //ha egy szam a vege akkor az egy id es elotte erroforras
        if (is_numeric($last)) {
            $last = $arrUri[count($arrUri) - 2];
        }
        return $last;
    }

    private static function getResources()
    {
        return array_filter(explode("/", trim($_SERVER['REQUEST_URI'], '/')));
    }

    private static function getResourceId()
    {
        $arrUri = self::getResources();
        $last = $arrUri[count($arrUri) - 1];
        if (is_numeric($last)) {
            return $last;
        }
        return false;
    }

    private static function getRequestData()
    {
        return json_decode(file_get_contents("php://input"), true);
    }


}