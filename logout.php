<?php
session_start();
require_once 'utils/helper.php';

session_destroy();
flash('ok', 'Logged out successfully');
redirect('index.php');
