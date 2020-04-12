<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="./fav.ico">
        <title>Youtube Comment Generator</title>
    </head>
    <?php 
    
    if (!session_id()) {
        session_start();
    }
    
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        
    } else {
        header('Location: login.php');
        exit;
    }

    $current_page = basename($_SERVER['PHP_SELF']);

    require_once('config.php');
    require_once('DbModel.php');

    ?>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="//code.jquery.com/jquery-3.3.1.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href=".">Youtube Comment Generator</a>
                </div>
                <ul class="nav navbar-nav">
                    <li <?php if ($current_page == 'index.php') echo 'class="active"'; ?> ><a href=".">Options</a></li>
                    <li <?php if ($current_page == 'keyword-list.php') echo 'class="active"'; ?> ><a href="./keyword-list.php">Keyword List</a></li>
                    <li <?php if ($current_page == 'comment-list.php') echo 'class="active"'; ?> ><a href="./comment-list.php">Comment List</a></li>
                    <li><a href="./logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
