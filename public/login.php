<?php require('../shared/initialize.php'); ?>

<?php

    if (isset($_POST['username']) and isset($_POST['password'])) {

        //3.1.1 Assigning posted values to variables.
        $username = $_POST['username'];
        $password = $_POST['password'];

        //check if the user is in the database:
        //$user = user_login($username, $password);
        $sql = "SELECT * FROM USER where username = '$username' and password = '$password'";
        $result = mysqli_query($db, $sql);

        $user_array = mysqli_fetch_assoc($result);
        $permission =  $user_array['role'];


        $count = mysqli_num_rows($result);


        if ($count == 1) {
            session_start();//starting session
            $_SESSION['username'] = $username;
            $_SESSION['permission'] = $permission;

            if ($_SESSION['permission'] == '1') {

                redirect_to('../admin/index.php?id=1');
            }

            elseif ($_SESSION['permission']  == '0') {
                redirect_to('../user/index.php');
            }
        }
     else { ?>
            <div id = 'warning' class = "col_12 column"
         <?php

        redirect_to('../public/login.php?id=0');
     }
?>
        </div>
        <?php   }

?>

<?php include('../shared/header.php');?>

    <?php
    if(isset($_GET['id'])){
    $id = $_GET['id'];
        if($id == '0'){
            ?>
            //Unsuccessful login:
            <div id = "note_area" class = "col_12 column">
                <div id = "alert_message">
                    <p>Your login has been unsuccessful due to incorrect user name or password. Please try again!
                    </p>
                </div>
            </div>
        <?php }
    }?>

    <div class="col_12 column">
        <form id="login_form" action = "" method = "post">
            <fieldset>
                <legend>Login to your account</legend>
                <p>
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" />
                </p>
                <p>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" />
                </p>
                <p>
                    <input type="submit" value="Submit" />
                </p>
            </fieldset>
        </form>
    </div>

    <div class="clearfix"></div>
<?php include('../shared/footer.php'); ?>
