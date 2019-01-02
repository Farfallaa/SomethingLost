<?php include('../shared/header.php');?>

  <?php  if(is_post_request()){
      $pass1 = $_POST['password'];
      $pass2 = $_POST['password2'];
      if($pass1===$pass2){ //if passwords match register and login user:

          $user = [];
          $user['fname'] = $_POST['first_name'];
          $user['lname'] = $_POST['last_name'];
          $user['username'] = $_POST['username'];
          $user['phone'] = $_POST['telephone'];
          $user['role'] = "0";
          $user['email'] = $_POST['email'];
          $user['password'] = $_POST['password'];

          $result = insert_user($user);
          //Insert succeeded:
          if($result = true) {
              session_start();
              $_SESSION['username'] = $user['username'];
              $_SESSION['permission'] = $user['role'];
              redirect_to('../user/index.php?id=1');
          }

          ?>
<!--          <div id = "notes_area" class = "col_12 column">-->
<!--              --><?php //  var_dump($user); ?>
<!--          </div>-->

    <?php  }

    else { //    IF PASSWORDS DO NOT MATCH - DISPLAY AN ERROR MESSAGE WITH THE SAME REGISTRATION FORM PRE-POPULATED:
          ?>
        <div id = "notes_area" class = "col_12 column">
            <?php echo "<p id = 'alert_message'>Your passwords do not match - please try again:</p>"; ?>
        </div>

<!--        Same form again with pre-populated fields:-->
        <div class="col_12 column">
            <form id="reg_form" action = "register.php" method = "post">
                <fieldset>
                    <legend>Create An Account</legend>
                    <p>
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" type="text" value = "<?php echo $_POST['first_name'];?>"  />
                    </p>
                    <p>
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" type="text" value = "<?php echo $_POST['last_name'];;?>" />
                    </p>
                    <p>
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" value = "<?php echo $_POST['username'];?>"/>
                    </p>
                    <p>
                        <label for="phone">Telephone</label>
                        <input id="telephone" name="telephone" type="tel" value = "<?php echo $_POST['telephone'];?>" />
                    </p>
                    <p>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value = "<?php echo $_POST['email'];?>" />
                    </p>
                    <p>
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" />
                    </p>
                    <p>
                        <label for="password2">Confirm Password</label>
                        <input id="password" name="password2" type="password" />
                    </p>

                    <p>
                        <input type="submit" value="Submit" />
                    </p>
                </fieldset>
            </form>
        </div>

    <?php   }

    ?>

<?php }//end of is-post-request
else{ //registration attempt the very first time:
?>
    <!--REGISTRATION FORM-->
    <div class="col_12 column">
        <form id="reg_form" action = "register.php" method = "post">
            <fieldset>
                <legend>Create An Account</legend>
                <p>
                    <label for="first_name">First Name</label>
                    <input id="first_name" name="first_name" type="text" />
                </p>
                <p>
                    <label for="last_name">Last Name</label>
                    <input id="last_name" name="last_name" type="text" />
                </p>
                <p>
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" />
                </p>
                <p>
                    <label for="phone">Telephone</label>
                    <input id="telephone" name="telephone" type="tel" />
                </p>
                <p>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" />
                </p>
                <p>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" />
                </p>
                <p>
                    <label for="password2">Confirm Password</label>
                    <input id="password" name="password2" type="password" />
                </p>

                <p>
                    <input type="submit" value="Submit" />
                </p>
            </fieldset>
        </form>
    </div>

    <?php } ?>

</div>
<!--fix all floats-->
<div class = "clarfix">	</div>

<?php include('../shared/footer.php');?>



