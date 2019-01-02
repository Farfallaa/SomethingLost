<?php  require('../shared/header.php');?>

    <div id = "notes_area" class = "col_12 column">
        <?php

        //PAGINATION bit:
        if(!isset($_GET['page'])){
            $page = 1;
        }
        else{
            $page = $_GET['page'];
        }
        //this is because POST expires in pages 2 and 3 and further so I wouldn't have $county_id, cityid and category ID variables
        //if I hadn't assign them to session!:
        //if this is the first page, variables are taken from post
        //if this is not the first page, variables are taken from Session.
        if($page != '1'){
            $county_id = $_SESSION['county'];
            $city_id = $_SESSION['city'];
            $category_id = $_SESSION['category'];
        }
        else{
            $county_id = $_POST["county"];
            $_SESSION['county'] = $county_id;//for pagination: to have these variables in pages 2, 3 and further

            $city_id = $_POST["city"];
            $_SESSION['city'] = $city_id;

            $category_id = $_POST["category"];
            $_SESSION['category'] = $category_id;
        }

//what happens if they leave first blank:
//        if($county_id = 'Select County'){
//            $county_id = find_county($city_id);
//        }

//        elseif(empty($city_id)){
//            $city_id = find_city($county_id);
//        }
//
//        elseif((empty($city_id))&& (empty($county_id))){
//            $city_id = '0';
//            $county_id = '0';
//        }

        $assoc_county = county_name($county_id);
        $county_name = $assoc_county['name'];

        $assoc_city = city_name($city_id);
        $city_name = $assoc_city['name'];

        $assoc_cat = category_name($category_id);
        $category_name = $assoc_cat['name'];




        echo "<div id = 'alert_message'><p>You have searched for:</p>";
        echo "<ul><li>".ucfirst($county_name) . "</li>";
        echo "<li>". ucfirst($city_name) . "</li>";
        echo "<li>". ucfirst($category_name) . "</li>";
        echo "</ul></p>";

        ?>
    </div>
        <?php



        $result_set = find_search_results($county_name, $city_name, $category_name);

        //PAGINATION:
        $num_of_results = mysqli_num_rows($result_set);
        $results_per_page = 3;//decide how many results are wanted per page
        $total_pages = ceil($num_of_results/$results_per_page);


        //determine sql LIMIT starting number:

        $starting_limit_number = ($page-1)*$results_per_page;

        //get table with limits:
        $search_set = find_search_with_limit($county_name, $city_name, $category_name, $starting_limit_number, $results_per_page);

        ?>



    <!--Search Area-->

    <div id = "search_area" class = "col_12 column">
        <form class = "horizontal" method = "post" action = "../public/search_results.php">
            <select id="countyID" name = "county">
                <option>Select County</option>
                <?php $county_set = find_all_counties();
                while($county = mysqli_fetch_assoc($county_set)) {//itterating through counties table and instead of rows, putting subjects to option tags!
                    echo "<option value=\"" . h($county['idcounty']) . "\"";
                    if($county["idcounty"] == $county_id) {
                        echo " selected";
                    }
                    echo ">" . h(ucfirst($county['name'])) . "</option>";
                }
                mysqli_free_result($county_set);
                ?>
            </select>
            <select id="countyID" name = "city" name = "category">
                <option>Select City</option>
                <?php $city_set = find_all_cities();
                while($city = mysqli_fetch_assoc($city_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                    echo "<option value=\"" . h($city['idcity']) . "\"";
                    if($city["idcity"] == $city_id) {
                        echo " selected";
                    }

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
                    if($category["idcategory"] == $category_id) {
                        echo " selected";
                    }

                    echo ">" . h(ucfirst($category['name'])) . "</option>";
                }
                mysqli_free_result($category_set);
                ?>
            </select>
            <button type = "submit">Submit</button>
        </form>
    </div>


    <!--SEARCH RESULTS-->



    <div class = "col_12 column">
    <h3>Search results</h3>
    <ul id = "listings">
        <?php while($advert = mysqli_fetch_assoc($search_set)) { ?>

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
<?php
echo    "Pages: ";
for ($page=1;$page<=$total_pages;$page++){
    echo '<a href = "../public/search_results.php?page='.$page.'">'.$page. '</a> ';
}
?>
<!--END OF PAGINATION-->

<?php include('../shared/footer.php');