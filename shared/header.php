<?php

require_once('initialize.php');
//Start the sesssion if it hasn't started yet:
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $permission = $_SESSION['permission'];
    }
//var_dump($_SESSION);
?>

<!-- BEGINNING OF HTML THAT IS SHARED BETWEEN ALL USER ROLES-->

    <!DOCTYPE html>
    <html>
    <head>
        <!-- META -->
        <title>Somethinglost|Welcome</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="description" content="" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../shared/css/kickstart.css" media="all" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../shared/style.css" media="all" />


    </head>
<div id = "container" class="grid">
    <body>
<?php
//lvar_dump($_SESSION);

////$username = $_SESSION['username'];
////$permission = $_SESSION['permission'];
//echo "username and permission is ".$username.$permission;

if(isset($username)){
    if($permission === '0') {
    ?>


 <!--        HTML of USER HEADER-->

<header>
	<div class = "col_6 column">
		<h1><a href="<?php echo url_for('/public/index.php');?>"><strong>Something</strong>Lost</a></h1>
	</div>
	<div class = "col_6 column right">
		<form id = "add_job_link" action = "<?php echo url_for('/user/index.php'); ?>">
            <button class="large green"><i class = "icon-plus"></i>Place Ad</button>
		</form>
	</div>

<!--Start of the navigation-->
<div class = "col_12 column">

    <nav>
        <!-- Menu Horizontal -->
        <ul class="menu">
            <li class="current"><a href="<?php echo url_for('/public/index.php');?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo url_for('/public/lost.php');?>"><i class="fa fa-search"></i> Browse Lost</a></li>
            <li><a href="<?php echo url_for('/public/found.php');?>"><i class="fa fa-search"></i> Browse Found</a></li>
            <li><a href="<?php echo url_for('/user/index.php');?>"><i class="fa fa-user-alt"></i> User Menu </a></li>
            <li><a href="<?php echo url_for('/public/logout.php');?>"><i class="fa fa-lock-open"></i> Logout </a></li>
        </ul>
    </nav>
</div>
        <!--END of the navigation-->

    <?php  } //end of user index if statement
    elseif($permission === '1'){
        //admin header: welcome message, admin index page and logout
    ?>

<!--        HTML OF ADMIN HEADER-->
        <header>
            <div class = "col_6 column">
                <h1><a href="<?php echo url_for('public/index.php');?>"><strong>Something</strong>Lost</a></h1>
            </div>
            <!-- THERE IS NO ADD Advert BUTTON IF THE USER IS ADMIN -->

        </header>

        <!--Start of the navigation-->
        <div class = "col_12 column">

            <nav>
                <!-- Menu Horizontal -->
                <ul class="menu">
                    <li class="current"><a href="<?php echo url_for('public/index.php');?>"><i class="fa fa-home"></i> Home</a></li>
                    <li><a href="<?php echo url_for('public/lost.php');?>"><i class="fa fa-search"></i> Browse Lost</a></li>
                    <li><a href="<?php echo url_for('public/found.php');?>"><i class="fa fa-search"></i> Browse Found</a></li>
                    <li><a href="<?php echo url_for('admin/index.php');?>"><i class="fa fa-user-alt"></i> Admin Menu</a></li>
                    <li><a href="<?php echo url_for('public/logout.php');?>"><i class="fa fa-lock-open"></i> Logout</a></li>
                </ul>
            </nav>
        </div>



    <?php }//end of admin index if statement

}//end of if isset username statement

else{ //what happens if $username is not set:

//regular header for public:

?>

<!--    PUBLIC HEADER WHEN USER IS NOT LOGGED IN -->
<header>
	<div class = "col_6 column">
		<h1><a href="<?php echo url_for('/public/index.php');?>"><strong>Something</strong>Lost</a></h1>
	</div>
	<div class = "col_6 column right">
		<form id = "add_job_link" action = "login.php">
            <button class="large green"><i class = "icon-plus"></i>Place Ad</button>
		</form>
	</div>
</header>

<!--Start of the navigation-->
<div class = "col_12 column">

    <nav>
        <!-- Menu Horizontal -->
        <ul class="menu">
            <li class="current"><a href="<?php echo url_for('public/index.php');?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo url_for('public/lost.php');?>"><i class="fa fa-search"></i> Browse Lost</a></li>
            <li><a href="<?php echo url_for('public/found.php');?>"><i class="fa fa-search"></i> Browse Found</a></li>
            <li><a href="<?php echo url_for('public/register.php');?>"><i class="fa fa-user-alt"></i> Register</a></li>
            <li><a href="<?php echo url_for('public/login.php');?>"><i class="fa fa-lock-open"></i> Login</a></li>
        </ul>
    </nav>
</div>

    <?php } ?>