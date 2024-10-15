<?php

$dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8'; // Db
$username = ''; // Username
$password = ''; // Password

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getIccidBySimId($simId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT iccid FROM sim
        WHERE iccid LIKE :simid
    ");
    $stmt->execute([':simid' => '%' . $simId]);
    return $stmt->fetchColumn();
}

function getSimBalance($iccid, $pdo) {
    $stmt = $pdo->prepare("
        SELECT balance FROM sim
        WHERE iccid = :iccid
    ");
    $stmt->execute([':iccid' => $iccid]);
    return $stmt->fetchColumn();
}

function transferBalance($fromSimId, $toSimId, $amount, $comment, $pdo) {
    $fromSimIccid = getIccidBySimId($fromSimId, $pdo);
    $toSimIccid = getIccidBySimId($toSimId, $pdo);

    if (!$fromSimIccid || !$toSimIccid) {
        return "SIM card(s) not found.";
    }

    try {
        $pdo->beginTransaction();

        $fromSimBalance = getSimBalance($fromSimIccid, $pdo);

        if ($fromSimBalance < $amount) {
            return "Insufficient balance on the source SIM card.";
        }

        $stmt = $pdo->prepare("
            INSERT INTO sim_balance_away (amount, iccid, comment)
            VALUES (:amount, :iccid, :comment)
        ");
        $stmt->execute([
            ':amount' => $amount,
            ':iccid' => $fromSimIccid,
            ':comment' => $comment
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO sim_balance_come (amount, iccid, comment)
            VALUES (:amount, :iccid, :comment)
        ");
        $stmt->execute([
            ':amount' => $amount,
            ':iccid' => $toSimIccid,
            ':comment' => $comment
        ]);

        $pdo->commit();

        return "Balance transfer successful.";
    } catch (Exception $e) {
        $pdo->rollBack();
        return "Error during balance transfer: " . $e->getMessage();
    }
}

// Example of function calls
$fromSimId = $_POST['from_simid'];
$toSimId = $_POST['to_simid'];
$amount = (float)$_POST['amount'];
$comment = $_POST['comment'];

echo transferBalance($fromSimId, $toSimId, $amount, $comment, $pdo);
