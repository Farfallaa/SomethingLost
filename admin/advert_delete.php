<?php  require('../shared/header.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: marke
 * Date: 08/06/2018
 * Time: 15:37
 */

//$permission = $_SESSION['permission'];
admin_lock($username, $permission);


if(!isset($_GET['id'])){
redirect_to('index.php');}
?>

<?php $id= $_GET['id'];

if(is_post_request()) {
    $id_set = find_ids($id);
    $result = delete_advert($id_set,$id);
    redirect_to('index.php?id=0');
}

else {
    $advert_set = find_advert_by_id($id);
    $advert = mysqli_fetch_assoc($advert_set);
}
?>

<div id = "notes_area" class = "col_12 column">
    <p><a href="index.php">&laquo; Back to Admin menu</a></p>

    <h1>Delete advert</h1>
    <p>Are you sure you want to delete the advert " <?php echo h($advert['advert_title']); ?>" ?</p>

    <form action="<?php echo ('advert_delete.php?id=' . h(u($advert['advert_id']))); ?>" method="post">
        <input type="submit"  value="Delete advert" />

    </form>

</div>
