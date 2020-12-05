<?php
    // File: inc.db.php

    const HOST = 'localhost';
    const USER = 'root';
    const PWD = 'Cun123456';
    const DB = 'kickstarter';

    const CONNECT_MYSQL = 'mysql:host=' . HOST . ';dbname=' . DB;

    $sql = "SELECT * FROM category
    WHERE category = '3D Printing';";
?>