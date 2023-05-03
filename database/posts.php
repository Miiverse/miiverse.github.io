<?php

session_start();
if(!isset($_GET['search'])) {
$redirect = '/database/users.php?1='.$_GET['1'].'&2='.$_GET['2'].'&order='.$_GET['order'];
} else {
$redirect = '/database/users.php?search='.$_GET['search'];
}
require('../lib/connect.php');

if(!isset($_GET['search'])) {
$limit_1 = mysqli_real_escape_string($db,$_GET['1']);
$limit_2 = mysqli_real_escape_string($db,$_GET['2']);
$order = mysqli_real_escape_string($db,$_GET['order']);
}

if(!$_SESSION['loggedin']) {
	exit('Please log in to continue.');
}

if($user['has_db_access'] == 0) {
	exit('Not authorized fagit.');
}

if(!isset($_GET['search'])) {
$query = $db->query("SELECT * FROM posts ORDER BY post_id $order limit $limit_1, $limit_2");
} else {
$query = $db->query("SELECT * FROM posts WHERE content LIKE '%".mysqli_real_escape_string($db,$_GET['search'])."%'");
}
$posts = mysqli_num_rows($query);

 ?>
<html>
	<head>
		<title>Staff Panel</title>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    	<link rel="shortcut icon" href="/img/icon.png">
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Staff Panel</h1>
				<a href="/database"><< Go back</a>
			</div>
			<a class="btn btn-primary" href="create_post.php"><span class="badge">+</span> Add post</a><br><br>
			<form action="posts.php" type="get">
				<div class="input-group">
					<input class="form-control" type="text" name="search" placeholder="Search post by content">
					<div class="input-group-btn">
						<input class="btn btn-default" type="submit" value="Search">
					</div>
				</div>
			</form>
			<div class="panel panel-default">
				<div class="panel-heading">Posts (Showing: <?php echo $posts; ?>)</div>
				<div class="panel-body">
					<ul class="list-group">
					<?php 

					while($list = mysqli_fetch_array($query)) {
						echo '<li class="list-group-item"><a href="/database/edit_post.php?id='.$list['post_id'].'">'.htmlspecialchars($list['content']).' '.($list['deleted'] > 0 ? '<span class="label label-danger">Deleted</span>' : '').'</a></li>';
					}

					?>
					</ul>
				</div>
			</div>
		</div>
</body>
