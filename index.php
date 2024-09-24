<?php
session_start();
include './vendor/autoload.php';

use App\Html\Request;

Request::handle();

//elég csak a abaserepository class ha a constructornak meg lehet adni a táblanevet. felesleges a city és a countyrepository ilyen kis projektnél.