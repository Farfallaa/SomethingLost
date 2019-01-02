<?php  require('../shared/header.php');?>

    <div id = "notes_area" class = "col_12 column">
<?php
//    SUCCESSFUL LOGOUT:
if(isset($_GET['id'])) {
    if($_GET['id']='1'){
        echo "<p id = 'alert_message'>You have logged out successfully</p>";
    }
        }
//        if(is_post_request()){
//        var_dump($_POST);
//        }
?>
    </div>
<?php
$result_set = find_all_ads();

//PAGINATION:
$num_of_results = mysqli_num_rows($result_set);
$results_per_page = 3;//decide how many results are wanted per page
$total_pages = ceil($num_of_results/$results_per_page);
//determine which page the visitor is currently on and assign to $page variable:
if(!isset($_GET['page'])){
    $page = 1;
}
else{
    $page = $_GET['page'];
}

//determine sql LIMIT starting number:

$starting_limit_number = ($page-1)*$results_per_page;

//get table with limits:
$advert_set = find_all_ads_with_limit($starting_limit_number, $results_per_page);

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
<!--LATEST ADs LISTING-->



<div class = "col_12 column">
	<h3>Latest lost and found</h3>
	<ul id = "listings">
        <?php while($advert = mysqli_fetch_assoc($advert_set)) { ?>

		<li>
<!--            ADVERT TYPE = LOST/FOUND button on the left (green/blue):-->
			<div class ="type">
			<?php if($advert['advert_type']=='1'){ ?>
			    <span class = "green">Found</span>
			<?php } // end of listing type = found statement
                    elseif($advert['advert_type']=='0'){?>
                        <span class = "blue">Lost</span>
                <?php } //end of listing type = lost statement?>
                <div class ="list_image">
                    <img class="align-left" src="../shared/images/<?php echo $advert['link'];?>" width="100" height="100" />
                </div>
            </div>


<!--            ADVERT BRIEF: ITEM NAME, LOCATION AND DESCRIPTION-->
            <div class = "description">
                <h5><?php echo $advert['item_name']." (".ucwords($advert['city']).", Co. ". ucwords($advert['county']).")"; ?></h5>
                <p>
                    <?php echo $advert['advert_descr']; ?>
                </p>
                <a href="<?php echo 'details.php?id=' . h(u($advert['advert_id'])); ?><i class = "fa fa-angle-double-right"></i>More..</a>

            </div>
        </li>
			<?php }//end of listings while statement ?>

	</ul>
<!--PAGINATION-->

    <div class='pagenumbers'>

<?php
    echo    "Pages: ";
for ($page1=1;$page1<=$total_pages;$page1++){

    if($page1 == $page){
        echo '<a class = "current" href = "index.php?page='.$page1.'">'.$page1. '</a> ';
    }
    else{
        echo '<a href = "index.php?page='.$page1.'">'.$page1. '</a> ';
    }


}

?>

    </div>

<?php include('../shared/footer.php');