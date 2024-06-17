<?php


function cndata()
{
    require "config.php";
    $connection = mysqli_connect($IP, $user, $pass, $DB, $port);
    if (mysqli_connect_errno()) {
        die("Falhou" . mysqli_connect_error());
    }
    return $connection;
}

function getids()
{
    $connection = cndata();

    $query = "SELECT * FROM queuepagamento";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $ids = array();
    while ($row = mysqli_fetch_array($result)) {
        $ids[] = $row;
    }
    mysqli_stmt_close($statement);
    mysqli_close($connection);
    return $ids;
}



function setpago($id)
{
    $connection = cndata();

    $query = "UPDATE queuepagamento SET pago= 1 where idmp = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    if (mysqli_stmt_execute($statement)) {
        mysqli_stmt_close($statement);
        mysqli_close($connection);
        return true;
    } else {
        mysqli_stmt_close($statement);
        mysqli_close($connection);
        return false;
    }
}

function setcancel($id)
{
    $connection = cndata();

    $query = "UPDATE queuepagamento SET pago= 9 where idmp = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    if (mysqli_stmt_execute($statement)) {
        mysqli_stmt_close($statement);
        mysqli_close($connection);
        return true;
    } else {
        mysqli_stmt_close($statement);
        mysqli_close($connection);
        return false;
    }
}

function insertmp($id, $produto)
{
    $conn = cndata();
    $sql = "INSERT INTO `queuepagamento` (`idmp`, `pago`, `produto`) VALUES (?, 0, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id, $produto);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false;
    }
}