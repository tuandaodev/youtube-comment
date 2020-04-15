<?php

if (!session_id()) {
    session_start();
}

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

} else {
    header('Location: login.php');
    exit;
}

require_once 'DbModel.php';
require_once 'functions.php';

$id = @$_REQUEST['campaign_id'];

$dbModel = new DbModel();
$data = [];
$data['campaign'] = $dbModel->get_campaign_by_id($id);
$data['groups'] = $dbModel->get_all_group($id);
$data['settings'] = $dbModel->get_campaign_options_all($id);
export_data($data);
exit;