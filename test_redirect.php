<?php
require_once 'config/database.php';
session_start();
// Simulate non-admin session
$_SESSION['user_id'] = 999;
$_SESSION['user_role'] = 'user';

// This should redirect to ../index.php
require_once 'admin/index.php';
