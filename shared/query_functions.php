<?php
require_once ('initialize.php');
?>

<?php

function insert_user($user){
    global $db;

    $sql = "INSERT INTO user ";
    $sql .= "(fname, lname, username, phone, role, email, password) ";
    $sql .= "VALUES (";
    $sql .= "'". db_escape($db, $user['fname']). "',";
    $sql .= "'". db_escape($db, $user['lname']). "',";
    $sql .= "'". db_escape($db, $user['username']) . "',";
    $sql .= "'". db_escape($db, $user['phone']) . "',";
    $sql .= "'". db_escape($db, $user['role']) . "',";
    $sql .= "'". db_escape($db, $user['email']) . "',";
    $sql .= "'". db_escape($db, $user['password']) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        echo "your query was ". $sql;
        db_disconnect($db);
        exit;
    }
}

function find_all_ads(){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE approved = '1' && published = '1' ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}


function find_all_ads_no_limit(){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}



function find_all_ads_user($id){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE iduser = ".$id." ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_all_ads_with_limit($start, $limit){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE approved = '1' && published = '1' ";
    $sql .= "LIMIT ".$start. ','. $limit. " ";
    //$sql .= "order by date_placed ";
//    echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

//counties that are used in adverts
function find_all_counties(){
    global $db;

    $sql  = "SELECT DISTINCT idcounty, name ";
    $sql .= "FROM county co ";
    $sql .= "INNER JOIN location l ON l.county_id = co.idcounty ";
    $result = mysqli_query($db, $sql);
    return $result;
}

//cities that are used in adverts
function find_all_cities(){
    global $db;

    $sql  = "SELECT DISTINCT idcity, name ";
    $sql .= "FROM city ct ";
    $sql .= "INNER JOIN location l ON l.city_id = ct.idcity ";
    $result = mysqli_query($db, $sql);
    return $result;
}

//categories that are used in adverts:
function find_all_categories(){
    global $db;

    $sql  = "SELECT DISTINCT idcategory, name ";
    $sql .= "FROM category ct ";
    $sql .= "INNER JOIN advert_view av ";
    $sql .= "ON ct.name = av.category ";


    $result = mysqli_query($db, $sql);
    return $result;
}

function county_name($id){
    global $db;

    $sql  = "SELECT name ";
    $sql .= "FROM county ";
    $sql .= "WHERE idcounty = '" .db_escape($db, $id). "'";
    //echo $sql;
   $result = mysqli_query($db, $sql);
   confirm_result_set($result, $sql);
   $county_name = mysqli_fetch_assoc($result);
   return $county_name;
}

function city_name($id){
    global $db;

    $sql  = "SELECT name ";
    $sql .= "FROM city ";
    $sql .= "WHERE idcity = '" .db_escape($db, $id). "'";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    $city_name = mysqli_fetch_assoc($result);
    return $city_name;
}

function category_name($id){
    global $db;

    $sql  = "SELECT name ";
    $sql .= "FROM category ";
    $sql .= "WHERE idcategory = '" .db_escape($db, $id). "'";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    $category_name = mysqli_fetch_assoc($result);
    return $category_name;
}

//find category from subcategory

function category_from_subcategory($id){
    global $db;

    $sql  = "SELECT category_id ";
    $sql .= "FROM kind ";
    $sql .= "WHERE idkind = '". $id. "'";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category;
}

function find_search_results($county, $city, $category){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE county = '". db_escape($db, $county) . "' ";
    $sql .= "AND city = '" . db_escape($db, $city) . "' ";
    $sql .= "AND category = '" . db_escape($db, $category) . "' ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_search_with_limit($county, $city, $category, $start, $limit){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE county = '". db_escape($db, $county) . "' ";
    $sql .= "AND city = '" . db_escape($db, $city) . "' ";
    $sql .= "AND category = '" . db_escape($db, $category) . "' ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_advert_by_id($id){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE advert_id = '".$id."'";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_all_lost_ads(){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE advert_type = '0' && approved = '1' && published = '1' ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_lost_with_limit($start, $limit){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE advert_type = '0' && approved = '1' && published = '1' ";
    $sql .= "ORDER by date_placed ";
    $sql .= "LIMIT ".$start. ','. $limit. " ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_all_found_ads(){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE advert_type = '1' && approved = '1' && published = '1' ";
    $sql .= "order by date_placed ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table
}

function find_found_with_limit($start, $limit){
    global $db;

    $sql = "SELECT * FROM advert_view ";
    $sql .= "WHERE advert_type = '1' && approved = '1' && published = '1' ";
    $sql .= "ORDER by date_placed ";
    $sql .= "LIMIT ".$start. ','. $limit. " ";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    return $result;// returns table

}

//ALL EXISTING COUNTIES
function find_counties(){
    global $db;

    $sql  = "SELECT DISTINCT idcounty, name ";
    $sql .= "FROM county ";

    $result = mysqli_query($db, $sql);
    return $result;
}


//ALL EXISTING CITIES
function find_cities(){
    global $db;

    $sql  = "SELECT DISTINCT idcity, name ";
    $sql .= "FROM city ";

    $result = mysqli_query($db, $sql);
    return $result;
}

//ALL EXISTING CATEGORIES
function find_categories(){
    global $db;

    $sql  = "SELECT DISTINCT idcategory, name ";
    $sql .= "FROM category ";

    $result = mysqli_query($db, $sql);
    return $result;
}


//ALL EXISTING SUBCATEGORIES
function find_all_subcategories(){
    global $db;

    $sql  = "SELECT DISTINCT idkind, name ";
    $sql .= "FROM kind ";

    $result = mysqli_query($db, $sql);
    return $result;
}


//function find_county($city_id){
//    global $db;
//
//    $sql  = "SELECT county_id ";
//    $sql .= "FROM city ";
//    $sql .= "WHERE idcity = '". $city_id. "'";
//
//    $result = mysqli_query($db, $sql);
//    confirm_result_set($result, $sql);
//    $county_id = mysqli_fetch_assoc($result);
//    return $county_id;
//
//}



//function find_city($county_id)    {
//    $sql  = "SELECT idcity ";
//    $sql .= "FROM city ";
//    $sql .= "WHERE county_id = '". $county_id. "'";
//
//    $result = mysqli_query($db, $sql);
//    confirm_result_set($result, $sql);
//    $city_id = mysqli_fetch_assoc($result);
//    return $city_id;
//}


//INSERT LOCATION

function new_location($advert){
    global $db;

    $sql = "INSERT INTO location ";
    $sql .= "(title, description, city_id, county_id) ";
    $sql .= "VALUES (";
    $sql .= "'".  db_escape($db,$advert['location_title']). "',";
    $sql .= "'".  db_escape($db,$advert['location_description']) . "',";
    $sql .= "'".  db_escape($db,$advert['city']) . "',";
    $sql .= "'" . db_escape($db,$advert['county']) . "' ";
    $sql .= ")";



    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

//INSERT PHOTO

function new_photo($url, $alt){
    global $db;

    $sql = "INSERT INTO photo ";
    $sql .= "(photo_url, alt_tag) ";
    $sql .= "VALUES (";
    $sql .= "'".  db_escape($db,$url). "',";
    $sql .= "'".  db_escape($db,$alt) . "' ";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }

    }

//INSERT ITEM
function new_item($advert,$photo_id){
    global $db;

    $sql = "INSERT INTO item ";
    $sql .= "(name, description, kind_id, photo_id, category_id) ";
    $sql .= "VALUES (";
    $sql .= "'".  db_escape($db,$advert['item_name']). "',";
    $sql .= "'".  db_escape($db,$advert['item_description']) . "',";
    $sql .= "'".  db_escape($db,$advert['kind']) . "',";
    $sql .= "'".  db_escape($db,$photo_id) . "',";
    $sql .= "'" . db_escape($db,$advert['category']) . "' ";
    $sql .= ")";



    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

//INSERT ADVERT
function new_advert($advert, $item_id, $location_id){
    global $db;

    $sql = "INSERT INTO advert ";
    $sql .= "(title, type, description, item_id, location_id) ";
    $sql .= "VALUES (";
    $sql .= "'".  db_escape($db,$advert['advert_title']). "',";
    $sql .= "'".  db_escape($db,$advert['type']). "',";
    $sql .= "'".  db_escape($db,$advert['advert_description']) . "',";
    $sql .= "'".  db_escape($db,$item_id ). "',";
    $sql .= "'" . db_escape($db,$location_id) . "' ";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function user_id($username){
     global $db;

    $sql  = "SELECT iduser ";
    $sql .= "FROM user ";
    $sql .= "WHERE username = '" .db_escape($db, $username). "'";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result, $sql);
    $user_id = mysqli_fetch_assoc($result);
    return $user_id;//array containing user id

}


//INSERT User_Advert
function user_advert($user_id, $advert_id){
    global $db;

    $sql = "INSERT INTO user_advert ";
    $sql .= "(date_placed, approved, published, resolved, user_id, advert_id) ";
    $sql .= "VALUES (";
    $sql .= "'".  date("Y/m/d"). "',";
    $sql .= "'0',";
    $sql .= "'0',";
    $sql .= "'0',";
    $sql .= "'" . $user_id . "', ";
    $sql .= "'" . $advert_id. "' ";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    if($result){
        return true;
    }
    else {
        //INSERT failed:
        return $sql;
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function approve_advert($id, $status){
global $db;

    $sql = "UPDATE user_advert SET approved='". db_escape($db,$status) ."' ";
    $sql .= "WHERE advert_id='" . db_escape($db, $id). "'";
    $result = mysqli_query($db, $sql);

 if($result){
     return true;
 }

 //approval failed
 else{
     return $sql;
     echo mysqli_error($db);
     echo "<br />"."your query was " . $sql;
     echo "<p><a href = 'index.php'>Return to homepage</a></p>";
     db_disconnect ($db);
     exit;
 }
}

function publish_advert($id, $status){
    global $db;

    $sql = "UPDATE user_advert SET published ='". db_escape($db,$status) ."' ";
    $sql .= "WHERE advert_id='" . db_escape($db, $id). "'";
    $result = mysqli_query($db, $sql);

    if($result){
        return true;
    }

    //approval failed
    else{
        return $sql;
        echo mysqli_error($db);
        echo "<br />"."your query was " . $sql;
        echo "<p><a href = 'index.php'>Return to homepage</a></p>";
        db_disconnect ($db);
        exit;
    }
}

//RESOLVE

function resolve_advert($id, $status){
    global $db;

    $sql = "UPDATE user_advert SET resolved ='". db_escape($db,$status) ."' ";
    $sql .= "WHERE advert_id='" . db_escape($db, $id). "'";
    $result = mysqli_query($db, $sql);

    if($result){
        return true;
    }

    //approval failed
    else{
        return $sql;
        echo mysqli_error($db);
        echo "<br />"."your query was " . $sql;
        echo "<p><a href = 'index.php'>Return to homepage</a></p>";
        db_disconnect ($db);
        exit;
    }
}



//EDIT advert

//SET OF FUNCTIONS TO GET REQUIRED IDS:

//1. location id
//2. photo id
//3. item id
//4. user id

function find_ids($id){
    global $db;

    $sql  = "select a.item_id, a.location_id, i.photo_id, ua.user_id ";
    $sql .= "from advert a, item i, user_advert ua ";
    $sql .= "where a.id = '". db_escape($db, $id)."' and a.item_id = i.iditem and a.id = ua.advert_id";
    
    $result = mysqli_query($db, $sql);

    confirm_result_set($result, $sql);

    $id_set = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $id_set; //returns an associatiative array
}

//EDIT LOCATION:

function edit_location($advert, $id)
{
    global $db;

    $sql = "UPDATE location SET ";
    $sql .= "title='" . db_escape($db, $advert['location_title']) . "', ";
    $sql .= "description='" . db_escape($db, $advert['location_description']) . "', ";
    $sql .= "city_id='" . db_escape($db, $advert['city']) . "', ";
    $sql .= "county_id='" . db_escape($db, $advert['county']) . "' ";
    $sql .= "WHERE idlocation='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

//UPDATE successful:
    confirm_result_set($result, $sql);
    if ($result) {
        return true;

    }
//UPDATE failed
    else {
        echo mysqli_error($db);
        echo "<br />" . "your query was " . $sql;
        echo "<p><a href = 'all_pages.php'>Return to all pages</a></p>";
        db_disconnect($db);
        exit;
    }

}

//UPDATE PHOTO

function edit_photo($img_url, $alt_tag, $photo_id)
{
    global $db;

    $sql = "UPDATE photo SET ";
    $sql .= "photo_url='" . db_escape($db, $img_url) . "', ";
    $sql .= "alt_tag='" . db_escape($db, $alt_tag) . "' ";
    $sql .= "WHERE idphoto='" . db_escape($db, $photo_id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

// successful:
    confirm_result_set($result, $sql);
    if ($result) {
        return true;

    }
// failed
    else {
        echo mysqli_error($db);
        echo "<br />" . "your query was " . $sql;
        echo "<p><a href = 'index.php'>Return to index</a></p>";
        db_disconnect($db);
        exit;
    }

}

//UPDATE ITEM

function edit_item($advert, $photo_id, $item_id){
    global $db;

    $sql = "UPDATE item SET ";
    $sql .= "name='" . db_escape($db, $advert['item_name']) . "', ";
    $sql .= "description='" . db_escape($db, $advert['item_description']) . "', ";
    $sql .= "kind_id='" . db_escape($db, $advert['kind']) . "', ";
    $sql .= "photo_id='" . db_escape($db, $photo_id) . "', ";
    $sql .= "category_id='" . db_escape($db, $advert['category']) . "' ";
    $sql .= "WHERE iditem='" . db_escape($db, $item_id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

//UPDATE successful:
    confirm_result_set($result, $sql);
    if ($result) {
        return true;

    }
//UPDATE failed
    else {
        echo mysqli_error($db);
        echo "<br />" . "your query was " . $sql;
        echo "<p><a href = 'index.php'>Return to index</a></p>";
        db_disconnect($db);
        exit;
    }
}

//UPDATE ADVERT:

function edit_advert($advert, $item_id, $location_id, $advert_id){
    global $db;

    $sql = "UPDATE advert SET ";
    $sql .= "title='" . db_escape($db, $advert['advert_title']) . "', ";
    $sql .= "type='" . db_escape($db, $advert['type']) . "', ";
    $sql .= "description='" . db_escape($db, $advert['advert_description']) . "', ";
    $sql .= "item_id='" . db_escape($db, $item_id) . "', ";
    $sql .= "location_id='" . db_escape($db,$location_id) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $advert_id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

//UPDATE successful:
    confirm_result_set($result, $sql);
    if ($result) {
        return true;

    }
//UPDATE failed
    else {
        echo mysqli_error($db);
        echo "<br />" . "your query was " . $sql;
        echo "<p><a href = 'index.php'>Return to index</a></p>";
        db_disconnect($db);
        exit;
    }
}

function delete_advert($id_set, $id){ //passing an array
    $location_id = $id_set['location_id'];
    $photo_id = $id_set['photo_id'];
    $item_id = $id_set['item_id'];
    $user_id = $id_set['user_id'];

    //1st Query: Location delete:

    global $db;

    $sql = "Delete FROM location ";
    $sql .= "WHERE idlocation = '" . db_escape($db, $location_id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query ($db, $sql);
    confirm_result_set($result, $sql);

    //2nd Query: photo delete:

    $sql1 = "Delete FROM photo ";
    $sql1 .= "WHERE idphoto = '" . db_escape($db, $photo_id) . "' ";
    $sql1 .= "LIMIT 1";
    $result1 = mysqli_query ($db, $sql1);
    confirm_result_set($result1, $sql1);

    //3rd Query: item
    $sql2 = "Delete FROM item ";
    $sql2 .= "WHERE iditem = '" . db_escape($db, $item_id) . "' ";
    $sql2 .= "LIMIT 1";
    $result2 = mysqli_query ($db, $sql2);
    confirm_result_set($result2, $sql2);

    //4th Query: user_advert
    $sql3 = "Delete FROM user_advert ";
    $sql3 .= "WHERE advert_id = '" . db_escape($db, $id) . "' ";
    $sql3 .= "LIMIT 1";
    $result3 = mysqli_query ($db, $sql3);
    confirm_result_set($result3, $sql3);

    //5th Query: advert
    $sql4 = "Delete FROM advert ";
    $sql4 .= "WHERE advert.id = '" . db_escape($db, $id) . "' ";
    $sql4 .= "LIMIT 1";
    $result4 = mysqli_query ($db, $sql4);
    confirm_result_set($result4, $sql4);

    if ($result = $result1 = $result2 = $result3 = $result4 = '1') {
        return true;
    } else {
        //DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }

}
