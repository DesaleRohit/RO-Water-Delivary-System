<?php

if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php?page=login");
    exit;
}
?>