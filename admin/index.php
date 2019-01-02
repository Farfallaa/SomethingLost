<?php
include('../shared/header.php');
?>

<?php
$username = $_SESSION['username'];
$permission = $_SESSION['permission'];

//prevent anyone else from accessing this page:
admin_lock($username, $permission);
?>

<?php $result_set = find_all_ads_no_limit();  ?>

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


//PUBLISH/UNPUBLISH ADVERTS

if(isset($_GET['publish'])){
    $id = $_GET['publish'];
    $result = publish_advert($id, 1);

    if($result){
        redirect_to('index.php?approve_success='.$id);
    }

    else{
        echo "Database query failed, your sql was ". $sql;
    }
}


if(isset($_GET['unpublish'])){
    $id =  $_GET['unpublish'];
    $result = publish_advert($id, 0);

    if($result){
        redirect_to('index.php?unapprove_success='.$id);
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
        }


        ?>

       <p>Hello, <?php echo $username; ?> Welcome to admin area :)</p>
        <p>Your permission is <?php echo $permission; ?> </p>


    </div>
<!--MAIN AREA-->
    <div class = "col_12 column">

        <!-- Table sortable -->
        <table class="tight">
<!--            table rows-->
            <thead><tr>
                <th>Advert type</th>
                <th>Advert title</th>
                <th>Created by</th>
                <th>Advert status</th>
                <th>Publishing status</th>
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
                <td><?php echo h($adverts['advert_title']); ?></td>
                <td><?php echo h($adverts['username']); ?></td>
                <!--APPROVED -->
                <td><?php
                    if($adverts['approved'] =='0'){
                        echo "Unapproved";
                    }
                    else if($adverts['approved'] =='1'){
                        echo "Approved";
                    }
                    ?>
                </td>
                <!--PUBLISHED -->
                <td><?php
                    if($adverts['published'] =='0'){
                        echo "Unpublished";
                    }
                    else if($adverts['published'] =='1'){
                        echo "Published";
                    }
                    ?>
                </td>

                <td><a href="<?php echo 'advert_edit.php?id=' . h(u($adverts['advert_id'])); ?>">View/Edit</a></td><!--brings to subjects/show/php and adds this id into link that can be passed to that show.php page..-->
                <td><a href="<?php echo 'advert_delete.php?id=' . h(u($adverts['advert_id'])); ?>">Delete</a></td>
<!--                APPROVED/UNAPPROVED-->
                <td><a href="<?php if($adverts['approved'] =='0'){
                    echo 'index.php?approve=' . h(u($adverts['advert_id']));
                    }
                    else if($adverts['approved'] =='1'){
                        echo 'index.php?unapprove=' . h(u($adverts['advert_id']));
                    }
                    ?>
                    ">
                        <?php
                        if($adverts['approved'] =='1'){
                            echo 'Unapprove';
                        }
                        else if($adverts['approved'] =='0'){
                            echo 'Approve';
                        }
                        ?>
                    </a>

                </td>
<!--                PUBLISH/UNPUBLISH-->
                <td><a href="
                    <?php if($adverts['published'] =='0'){ //case:unpublished
                        echo 'index.php?publish=' . h(u($adverts['advert_id']));
                    }
                    else if($adverts['published'] =='1'){ //case published
                        echo 'index.php?unpublish=' . h(u($adverts['advert_id']));
                    }
                    ?>
                    ">
                        <?php
                        if($adverts['published'] =='0'){
                            echo 'Publish';
                        }
                        else if($adverts['published'] =='1'){
                            echo 'Unpublish';
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

