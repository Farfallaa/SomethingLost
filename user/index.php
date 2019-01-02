<?php  require('../shared/header.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: marke
 * Date: 08/06/2018
 * Time: 15:37
 */
$username = $_SESSION['username'];
$permission = $_SESSION['permission'];

user_lock($username, $permission);

?>


<?php
if(is_post_request()) {

    //construct location title and advert title from other variables:
    //City name variable:
    $city_id = $_POST['city'];
    $county_id = $_POST['county'];

    $assoc_county = county_name($county_id);
    $county_name = $assoc_county['name'];

    $assoc_city = city_name($city_id);
    $city_name = $assoc_city['name'];


    $location_title = ucfirst($city_name)." (".ucfirst($county_name).")";

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

    // Insert location:

    $result1 = new_location($advert);

    if($result1 === true) {
        $location_id = mysqli_insert_id($db);
        }

        //  UPLOAD NEW FILE //
//    $_FILES - global variable Files that can have many files.
//    In this case my file that Im working with is called 'file'
    $file = $_FILES['file'];
//define the size of file:

    $fileSize = $_FILES['file']['size'];
    //check if filesize < 0 (then nothing has been uploaded)
    //FILE UPLOAD NOT ATTEMPTED: USE DEFAULT


    //if(empty($_FILES['file'])){
    if($fileSize === 0){
        $img_url = 'No-image-available.jpg';
        $alt_tag = 'No image available';
        $result2 = new_photo($img_url, $alt_tag);
        if($result2 === true) {
            $photo_id = mysqli_insert_id($db);
        }

//        $photo_id = 11;

    }

    //FILE UPLOAD ATTEMPT:
    else{


    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];//temp location of the file
    $fileSize = $_FILES['file']['size'];
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

        //insert new image url:

        $result2 = new_photo($img_url, $alt_tag);
        if($result2 === true) {
            $photo_id = mysqli_insert_id($db);
        }

    }



//if image is uploaded: use the image that is uploaded:

//        Insert item (with image):
    $result3 = new_item($advert,$photo_id);

        if($result3 === true) {
            $item_id = mysqli_insert_id($db);
            }

//            Insert advert:


    $result4 = new_advert($advert, $item_id, $location_id);
            if($result4 === true){
                $advert_id = mysqli_insert_id($db);
            }

            $user_id_assoc = user_id($username);
            $user_id = $user_id_assoc['iduser'];

            //Connect user:

    $result5 = user_advert($user_id, $advert_id);
    if($result5 === true){
        $user_advert_id = mysqli_insert_id($db);
        redirect_to('all_adverts.php?id=1');
    }





?>


<div id = "notes_area" class = "col_12 column">
    <?php echo "<p id = 'alert_message'>file_size= ".$fileSize. " image url: ".$img_url." alttag: ".$alt_tag." photo_id: ".$photo_id. " location_id=".$location_id . "item_id = ".$item_id."advert_id = ".$advert_id."user_advert_id = ".$user_advert_id."</p>"; ?>
    <?php echo " this is the array: </br>";
    var_dump($advert);
    ?>


</div>

<?php }


//DISPLAY FORM
else {
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
    <?php echo "<p id = 'alert_message'>Please fill in the form to create new ad:</p><br/>";
    echo "<p><a href=\"all_adverts.php\">Or Click to check your existing Adverts &laquo;</a></p>"
    ?>
</div>


<!--REGISTER NEW AD FORM-->
<div class="col_12 column">
    <form id="reg_form" action = "index.php" method = "post" enctype = "multipart/form-data">
        <fieldset>
            <legend>Register New Ad</legend>
            <p>
                Is this lost or found:
                <input type="hidden" name="type" value="0" /> <!--if nothing is selected 0 comes by default -->
                <input type="radio" name="type" id="radio1" value = '0' />
                <label for="0" class="inline">Lost</label>
                <input type="radio" name="type" id="radio1" value = '1' />
                <label for="1" class="inline" >Found</label>
            </p>
            <p>
            <p>
                <!-- Select Subcategory -->
                <label for="subcategory">Select Subcategory</label>
                <select id = "category_select" name = "kind">
                    <option>Select Subcategory</option>
                    <?php $subcategory_set = find_all_subcategories();
                    while($subcategory = mysqli_fetch_assoc($subcategory_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                        echo "<option value=\"" . h($subcategory['idkind']) . "\"";

                        echo ">" . h(ucfirst($subcategory['name'])) . "</option>";
                    }
                    mysqli_free_result($subcategory_set);
                    ?>
                </select>
            </p>
            <p>
                <label for="item_name">Item name</label>
                <input id="item" name="item_name" type="text" />
            </p>
            <p>
                <label for="item_description">Item description</label>

                <!-- Textarea -->
                <textarea id="item_description" name = "item_description" placeholder="Describe the item lost or found the best you can in max 250 words"></textarea>
            </p>
            <p>
                <label for="advert_description"> Advert text</label>
                <!-- Textarea -->
                <textarea id="description" name = "advert_description" placeholder="Advert text max 250 words: this is where you can offer rewards in case of lost and give circumstances if you found an item"></textarea>
            </p>
            <p>
                <label for="county">Select County</label>
                <select id="countyID" name = "county">
                    <option value="0">-- County --</option>
                    <?php $county_set = find_counties();
                    while($county = mysqli_fetch_assoc($county_set)) {//itterating through counties table and instead of rows, putting subjects to option tags!
                        echo "<option value=\"" . h($county['idcounty']) . "\"";
                        echo ">" . h(ucfirst($county['name'])) . "</option>";
                    }
                    mysqli_free_result($county_set);
                    ?>
                </select>
            </p>
            <p>
                <label for="select1">Select City</label>
                <select id="countyID" name = "city">
                    <option value="0">-- City --</option>
                    <option>Select City</option>
                    <?php $city_set = find_cities();
                    while($city = mysqli_fetch_assoc($city_set)) {//itterating through cities table and instead of rows, putting subjects to option tags!
                        echo "<option value=\"" . h($city['idcity']) . "\"";

                        echo ">" . h(ucfirst($city['name'])) . "</option>";
                    }
                    mysqli_free_result($city_set);
                    ?>
                </select>
            </p>
            <p>
                <label for="location_description"> Location description </label>
                <!-- Textarea -->
                <textarea id="textarea1" name="location_description" placeholder="Describe in more detail where you found/lost the item"></textarea>
            </p>
            <p>
                <label for="file_url">Image name</label>
                <input id="item" name="file_alt" type="text" />
            </p>
            <p>
                <label for="file">Add image</label>
                <input id = "image" name="file" type="file" />
            </p>

            <p>
                <input type="submit" value="Submit" />
            </p>
        </fieldset>
    </form>
</div>
