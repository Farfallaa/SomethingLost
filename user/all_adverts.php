<?php
include('../shared/header.php');
?>

<?php
//prevent anyone else from accessing this page:
user_lock($username, $permission);
?>

<?php
$user_array  = user_id($username);
$user_id = $user_array['iduser'];

$result_set = find_all_ads_user($user_id);  ?>

<?php


//APPROVE/DISSAPROVE adverts

if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    $result = approve_advert($id, 1);

    if($result){
        redirect_to('index.php?approve_success='.$id);
    }

    else{
        echo "Database query failed, your sql was ". $sql;
    }
}


if(isset($_GET['unapprove'])){
    $id =  $_GET['unapprove'];
    $result = approve_advert($id, 0);

    if($result){
        redirect_to('index.php?unapprove_success='.$id);
    }

    else{
        echo "Database query failed, your sql was ". $sql;
    }
}


//Resolve/UNresolve ADVERTS

if(isset($_GET['resolve'])){
    $id = $_GET['resolve'];
    $result = resolve_advert($id, 1);

    if($result){
        redirect_to('all_adverts.php?resolve_success='.$id);
    }

    else{
        echo "Database query failed, your sql was ". $sql;
    }
}


if(isset($_GET['unresolve'])){
    $id =  $_GET['unresolve'];
    $result = resolve_advert($id, 0);

    if($result){
        redirect_to('all_adverts.php?unresolve_success='.$id);
    }

    else{
        echo "Database query failed, your sql was ". $sql;
    }
}


?>

<div id = "notes_area" class = "col_12 column">
    <?php
    if(isset($_GET['id'])){
        if($_GET['id'] == '0'){
            echo "<p id = 'alert_message'>The ad has been successfully deleted!</p><br/>";
        }
        elseif($_GET['id']=='1'){
            echo "<p id = 'alert_message'>Your ad has been successfully created and will be published as soon as the admin approves it! This should only take up to 24 hours.</p><br/>";
        }
    }


    ?>

    <p>Hello, <?php echo $username; ?>, Welcome to user area :)</p>


</div>
<!--MAIN AREA-->
<div class = "col_12 column">

    <!-- Table sortable -->
    <table class="tight">
        <!--            table rows-->
        <thead><tr>
            <th>Advert type</th>
            <th>Item title</th>
            <th>Resolved?</th>
            <th>Approved?</th>
            <th>Published?</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>


        </tr></thead>
        <tbody>
        <?php while($adverts = mysqli_fetch_assoc($result_set)) { ?>
            <tr>
                <td><?php
                    if($adverts['advert_type'] =='0'){
                        echo "Lost";
                    }
                    else if($adverts['advert_type'] =='1'){
                        echo "Found";
                    }
                    ?>
                </td>
                <td><?php echo h($adverts['item_name']); ?></td>

                <!--RESOLVED -->
                <td><?php
                    if($adverts['resolved'] =='0'){
                        echo "No";
                    }
                    else if($adverts['resolved'] =='1'){
                        echo "Yes";
                    }
                    ?>
                </td>
                <!--APPROVED -->
                <td><?php
                    if($adverts['approved'] =='0'){
                        echo "No";
                    }
                    else if($adverts['approved'] =='1'){
                        echo "Yes";
                    }
                    ?>
                </td>
                <!--PUBLISHED -->
                <td><?php
                    if($adverts['published'] =='0'){
                        echo "No";
                    }
                    else if($adverts['published'] =='1'){
                        echo "Yes";
                    }
                    ?>
                </td>

                <td><a href="<?php echo 'advert_edit.php?id=' . h(u($adverts['advert_id'])); ?>">View/Edit</a></td><!--brings to subjects/show/php and adds this id into link that can be passed to that show.php page..-->
                <td><a href="<?php echo 'advert_delete.php?id=' . h(u($adverts['advert_id'])); ?>">Delete</a></td>
                <!--                RESOLVE/UNRESOLVE-->
                <td><a href="<?php if($adverts['resolved'] =='0'){
                        echo 'all_adverts.php?resolve=' . h(u($adverts['advert_id']));
                    }
                    else if($adverts['resolved'] =='1'){
                        echo 'all_adverts.php?unresolve=' . h(u($adverts['advert_id']));
                    }
                    ?>
                    ">
                        <?php
                        if($adverts['resolved'] =='1'){
                            echo 'Unresolve';
                        }
                        else if($adverts['resolved'] =='0'){
                            echo 'Resolve';
                        }
                        ?>
                    </a>
                </td>
            </tr>
        <?php }
        mysqli_free_result($result_set);
        ?>

        </tbody>
    </table>


    <?php include('../shared/footer.php'); ?>


</div>
