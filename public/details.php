<?php  require('../shared/header.php');?>

<!--<div id = "notes_area" class = "col_12 column">-->
<!--  -->
<!--</div>-->
<?php

if(!isset($_GET['id'])){
    redirect_to('index.php');
}
else{
    $advert_id = $_GET['id'];
    $advert_set = find_advert_by_id($advert_id);
    $advert = mysqli_fetch_assoc($advert_set); //creates and array of data

    ?>



<!--Search Area-->

<div id = "search_area" class = "col_12 column">
    <form class = "horizontal" method = "post" action = "../public/search_results.php">
        <select id="countyID" name = "county">
            <option>Select County</option>
            <?php $county_set = find_all_counties();
            while($county = mysqli_fetch_assoc($county_set)) {//itterating through counties table and instead of rows, putting subjects to option tags!
                echo "<option value=\"" . h($county['idcounty']) . "\"";
                echo ">" . h(ucfirst($county['name'])) . "</option>";
            }
            mysqli_free_result($county_set);
            ?>
        </select>
        <select id="countyID" name = "city">
            <option>Select City</option>
            <?php $city_set = find_all_cities();
            while($city = mysqli_fetch_assoc($city_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                echo "<option value=\"" . h($city['idcity']) . "\"";

                echo ">" . h(ucfirst($city['name'])) . "</option>";
            }
            mysqli_free_result($city_set);
            ?>
        </select>


        <select id = "category_select" name = "category">
            <option>Select Category</option>
            <?php $category_set = find_all_categories();
            while($category = mysqli_fetch_assoc($category_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                echo "<option value=\"" . h($category['idcategory']) . "\"";

                echo ">" . h(ucfirst($category['name'])) . "</option>";
            }
            mysqli_free_result($category_set);
            ?>
        </select>
        <button type = "submit">Submit</button>
    </form>
</div>

<!--ADD DETAILS-->

<div class = "col_12 column">
    <h1><?php echo $advert['advert_title']?></h1>

    <p><?php echo $advert['advert_descr']?></p>
    <h5>Location: <?php echo $advert['location']. " (". $advert['location_descr'].")."; ?></h5>

    <div class ="list_image">
        <img class="caption" title="<?php echo $advert['alt_tag']; ?>" src="../shared/images/<?php echo $advert['link'];?>" width="400" height="350" />

    </div>
<h2>Contact details:</h2>
    <p><strong>Name: </strong> <?php echo $advert['user_f_name'];?></p>
    <p><strong>Telephone number: </strong> <?php echo $advert['user_phone']; ?></p>

    <?php include('../shared/footer.php'); ?>
</div>


 <?php   } ?>


