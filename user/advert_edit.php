<?php  require('../shared/header.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: marke
 * Date: 08/06/2018
 * Time: 15:37
 */

//$permission = $_SESSION['permission'];
user_lock($username, $permission);

?>


<?php
if(is_post_request()) {
    $id = $_GET['id']; //Id of the advert to be edited

    //construct location title and advert title from other variables:
    //City name variable:
    $city_id = $_POST['city'];
    $county_id = $_POST['county'];

    $assoc_county = county_name($county_id);
    $county_name = $assoc_county['name'];

    $assoc_city = city_name($city_id);
    $city_name = $assoc_city['name'];


    $location_title = ucfirst($city_name)." (".ucfirst($county_name).")"; //ucfirst = first cap letter

    //Determine lost or found:
    if($_POST['type'] == '0'){
        $type = "lost";
    }
    elseif($_POST['type'] == '1'){
        $type = "found";
    }
    $item_name = $_POST['item_name'];
    $advert_title = $item_name. " was " . $type . " in ". $location_title;

    $subcategory = $_POST['kind']; //id kind
    $assoc_category = category_from_subcategory($subcategory);
    $category = $assoc_category['category_id'];

    //put all form values into an array:
    $advert = [];
    $advert['type'] = $_POST['type'];
    $advert['category'] = $category;
    $advert['kind'] = $subcategory;
    $advert['item_name'] = $_POST['item_name'];
    $advert['item_description'] = $_POST['item_description'];
    $advert['advert_title'] = $advert_title;
    $advert['advert_description'] = $_POST['advert_description'];
    $advert['county'] = $_POST['county'];
    $advert['city'] = $_POST['city'];
    $advert['location_title'] = $location_title;
    $advert['location_description'] = $_POST['location_description'];

    // Extra info required:
    //1. location id
    //2. photo id
    //3. item id
    //4. user id


//location id when advert id is known:

    $id_set = find_ids($id);
    $location_id = $id_set['location_id'];
    $photo_id = $id_set['photo_id'];
    $item_id = $id_set['item_id'];
    $user_id = $id_set['user_id'];

//check if location update is success or not:
    $result1 = edit_location($advert, $location_id);

    if($result1){
        $location_edit = "1";
    }
    else{
        $location_edit = "0";
    }

//  UPLOAD  FILE //
//    $_FILES - global variable Files that can have many files.
//    In this case my file that Im working with is called 'file'
    $file = $_FILES['file'];

    //FILE UPLOAD NOT ATTEMPTED: Dont do anything
    //FILE UPLOAD  ATTEMPTED: change file:

    $fileSize = $_FILES['file']['size'];

    //TEST IF FILE UPLOAD WAS ATTEMPTED:

    if($fileSize > 0){
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];//temp location of the file
        $fileError = $_FILES['file']['error'];//if there is an error I need to stop uploading the file
        $fileType = $_FILES['file']['type'];

        //to make sure it is a jpg file I need to split the extension part:
        $fileExt = explode('.',$fileName);
        //I convert extension to lowercase and only use last bit of the above explode to make sure
        // I only take in the extension

        //end function gets the last piece of data from an array and strtolower() turns it to lowercase
        $fileActualExt = strtolower(end($fileExt));

        //telling which files are allowed by creating an array of permitted
        //file extensions:
        $allowed = array('jpg','jpeg', 'png');

        //check if the file belongs to the array:
        if(in_array($fileActualExt, $allowed)) {
            //1. check for errors in an upload:
            if ($fileError === 0) { //no errors
                //2.check the size:
                if ($fileSize < 500000) { //allowing max 500mb images
                    //3. start uploading the file:
                    //a.set a proper name:
                    //we want to assign unique id to every file that gets uploaded to our system
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    //b.tell where you want this file to be stored in root folder
                    $fileDestination = '../shared/images/' . $fileNameNew;

                    //c. function to move the file from temp location to the actual location:
                    move_uploaded_file($fileTmpName, $fileDestination);

                } else {
                    echo "the file is too big, please upload an image less than 500mb large";
                }
            } else { //there was an error
                echo "there was an error uploading your file. please try again!";
            }
        }
        else{
            echo "you cannot upload files of this type! Only files
        ending .jpg, .jpeg and .png are permitted";
        }

        $img_url = $fileNameNew;
        $alt_tag = strtolower($_POST['file_alt']);

        //pass 3 variables into function: img url, alt tag, photo ID
        $result2 = edit_photo($img_url, $alt_tag, $photo_id);
        //check if photo has been updated:
        if($result2){
            $photo_edit = "1";
        }
        else{
            $photo_edit = "0";
        }

    }
    else{ //File size =0
        //Photo not updated:
        $photo_edit = "1";

    }


//        UPDATE item (with photo):
    $result3 = edit_item($advert,$photo_id, $item_id);
    if($result3 === true) {
        $item_edit = "1";
    }
    else{
        $item_edit = "0";
    }

//      UPDATE advert:


    $result4 = edit_advert($advert, $item_id, $location_id, $id);
    if($result4 === true){
        $advert_edit = "1";
    }
    else{
        $advert_edit = "0";
    }

    ?>


    <div id = "notes_area" class = "col_12 column">
        <?php if($location_edit = $photo_edit = $item_edit = $advert_edit = '1'){
            echo "<p id = 'alert_message'>Update was successful. </p><br/>";
            echo "<p id = 'alert_message'><a href= \"index.php\">&laquo; Back to Admin Menu</a></p><br/>";
            echo "<p id = 'alert_message'><a href= \"advert_edit.php?id=".$id."\">&laquo; Back to View/Edit Advert</a></p>";
        }
        else{
            redirect_to('advert_edit.php?id=".$id.');
        }

        ?>

    </div>

<?php }


//DISPLAY FORM with pre-populated choices
else {
    if(!isset($_GET)){
        redirect_to(index.php); //if admin got here by accident redirect to index where he can choose the advert to edit
    }
    else{
        $id = $_GET['id'];
        $result_set = find_advert_by_id($id);
        $orig_array = mysqli_fetch_assoc($result_set);



        // include('../header.php');
        //initialize a new assoc array and set its default values:
        $advert = [];
        $advert['type'] = '';
        $advert['category'] = '';
        $advert['kind'] = '';
        $advert['name'] = '';
        $advert['item_description'] = '';
        $advert['advert_title'] = '';
        $advert['advert_description'] = '';
        $advert['county'] = '';
        $advert['city'] = '';
        $advert['location_title'] = '';
        $advert['location_description'] = '';
        $advert['file_alt'] = '';
        $advert['file'] = '';

    }

    ?>



    <div id = "notes_area" class = "col_12 column">
        <?php echo "<p id = 'alert_message'>Here you can view and/or update the ad called: \"".$orig_array['advert_title']."\".</p>"; ?>
    </div>



    <!--EDIT AD NEW AD FORM-->
    <div class="col_12 column">
        <form id="reg_form" action = "advert_edit.php?id=<?php echo $id;?>" method = "post" enctype = "multipart/form-data">
            <fieldset>
                <legend>Edit advert</legend>
                <p>
                    Is this lost or found:
                    <input type="hidden" name="type" value="0" /> <!--if nothing is selected 0 comes by default -->
                    <input type="radio" name="type" id="radio1" value = '0'
                        <?php if($orig_array['advert_type']== '0'){
                            echo "checked";
                        }
                        ?>
                    />
                    <label for="0" class="inline">Lost</label>
                    <input type="radio" name="type" id="radio1" value = '1'
                        <?php if($orig_array['advert_type']== '1'){
                            echo "checked";
                        }
                        ?>
                    />
                    <label for="1" class="inline" >Found</label>
                </p>
                <p>
                <p>
                    <!-- Select Subcategory -->
                    <label for="subcategory">Select Subcategory</label>
                    <select id = "category_select" name = "kind">
                        <?php $subcategory_set = find_all_subcategories();
                        while($subcategory = mysqli_fetch_assoc($subcategory_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                            echo "<option value=\"" . h($subcategory['idkind']) . "\"";
                            if($subcategory['name'] == $orig_array['kind']){
                                echo " selected";
                            }
                            echo ">" . h(ucfirst($subcategory['name'])) . "</option>";
                        }
                        mysqli_free_result($subcategory_set);
                        ?>
                    </select>
                </p>
                <p>
                    <label for="item_name">Item name</label>
                    <input id="item" name="item_name" type="text" value = "<?php echo $orig_array['item_name'];?> " />
                </p>
                <p>
                    <label for="item_description">Item description</label>

                    <!-- Textarea -->
                    <textarea id="item_description" name = "item_description">
                    <?php echo $orig_array['item_descr']; ?>
                </textarea>
                </p>
                <p>
                    <label for="advert_description"> Advert text</label>
                    <!-- Textarea -->
                    <textarea id="description" name = "advert_description">
                    <?php echo $orig_array['advert_descr']; ?>
                </textarea>
                </p>
                <p>
                    <label for="county">Select County</label>
                    <select id="countyID" name = "county">
                        <?php $county_set = find_counties();
                        while($county = mysqli_fetch_assoc($county_set)) {//itterating through counties table and instead of rows, putting subjects to option tags!
                            echo "<option value=\"" . h($county['idcounty']) . "\"";
                            if($county['name'] == $orig_array['county']){
                                echo " selected";
                            }
                            echo ">" . h(ucfirst($county['name'])) . "
                    </option>";
                        }
                        mysqli_free_result($county_set);
                        ?>
                    </select>
                </p>
                <p>
                    <label for="select1">Select City</label>
                    <select id="countyID" name = "city">
                        <?php $city_set = find_cities();
                        while($city = mysqli_fetch_assoc($city_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                            echo "<option value=\"" . h($city['idcity']) . "\"";
                            if($city['name'] == $orig_array['city']){
                                echo " selected";
                            }

                            echo ">" . h(ucfirst($city['name'])) . "</option>";
                        }
                        mysqli_free_result($city_set);
                        ?>
                    </select>
                </p>
                <p>
                    <label for="location_description"> Location description </label>
                    <!-- Textarea -->
                    <textarea id="textarea1" name="location_description">
                    <?php
                    echo $orig_array['location_descr'];
                    ?>
                </textarea>
                </p>
                <p>
                    <label for="file_url">Image name</label>
                    <input id="item" name="file_alt" type="text" value = <?php echo $orig_array['alt_tag']; ?> />
                </p>
                <p>Current image:</p>
                <img class="caption" title="<?php echo $orig_array['item_descr']; ?>" src="../shared/images/<?php echo $orig_array['link']; ?>" width="400" height="350" />
                <p>
                    <label for="file">Add New Image</label>
                    <input id = "image" name="file" type="file" />
                </p>

                <p>
                    <input type="submit" value="Edit" />

                </p>

        </form>
        <br/>
        <a class="button" href="advert_delete.php?id=<?php echo $id;?>">Delete</a>
        <br/>
        <br/>

        <a class="button" href="index.php">Back to menu</a>

        </fieldset>
        <?php include('../shared/footer.php'); ?>
    </div>

<?php } ?>
