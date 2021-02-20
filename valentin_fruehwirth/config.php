<?php
try{
    $server = 'localhost';
    $db = 'theater';
    $user = 'root';
    $pwd = '';
    $con = new PDO('mysql:host='.$server.';dbname='.$db.
        ';charset=utf8', $user, $pwd);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e)
{
    switch ($e->getCode())
    {
        case 1045:
            echo "User und oder Passwort"." ".$user."/".$pwd." "."sind nicht korrekt";
            break;
        case 1049:
            echo "DB"." ".$db." "."existiert nicht";
            break;
        case 2002:
            echo "Server ".$server." existiert nicht";
    }

    // Fehlerbehandlung
    echo $e->getMessage().'<br>';
}