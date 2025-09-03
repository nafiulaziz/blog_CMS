<?php
session_start();
require_once '../src/Auth.php';

use LH\Auth;

Auth::logout();
header('Location: login.php');
exit;