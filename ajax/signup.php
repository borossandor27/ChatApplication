<?php
require_once './config.php';
$fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_STRING);
$lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $result = mysqli_query($conn,"SELECT lname FROM `users` WHERE `email`='{$email}';" );
        if( mysqli_num_rows($result) > 0){
            echo "$email - már létezik ez az email cím!"; 
        } else {
            //-- új email címet adott meg ------------------------
            if(isset($_FILES['image'])){
                //-- Képfájl vizsgálata --------------------------
                $img_name = $_FILES['image']['name'];
                $img_type = $_FILES['image']['type'];
                $tmp_name = $_FILES['image']['tmp_name'];
                //-- Vizsgálat a kiterjesztésre ------------------
                $img_explode = explode('.', $img_name);
                $img_ext = end($img_explode);
                $extensions = ['png', 'jpg', 'jpeg'];
                if(in_array($img_ext, $extensions) === true){
                    //-- képfájl típusa megfelelő ------------
                    $time = time(); //
                    $new_img_name = $time.$img_name;
                    $destination = "..".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.$new_img_name;
                    if(move_uploaded_file($tmp_name, $destination)){
                        $status = "Active now";
                        $random_id = 0;
                        $egyedi = false;
                        do {
                            $random_id = rand(1000000000, 9000000000); //-- 9 000 000 000
                            $sql = "SELECT `unique_id` FROM `users` WHERE `unique_id` = '{$random_id}'; ";
                            $result = mysqli_query($conn, $sql);
                        } while (mysqli_num_rows($result) > 0);
                        
                        //-- új felhasználó adatainak kiírása az adatbázisba --------------------------
                        $sql = "INSERT INTO `users` (`unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`) "
                                . "VALUES ('{$random_id}', '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}');";
                        try {
                            $result = mysqli_query($conn, $sql);
                        } catch (Exception $exc) {
                            echo 'Sikertelen rögzítés\n'.$exc;
                        }



                        if($result){
                            //-- a felhasználó regisztrálása sikeres!
                            $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                            if(mysqli_num_rows($sql3) > 0){
                                $row = mysqli_fetch_assoc($sql3);
                                $_SESSION['unique_id'] = $row['unique_id'];
                                echo 'success';
                            }
                        } else {
                            echo 'A regisztráció sikertelen!';
                        }
                    } 
                } else {
                    echo 'Kérem jpeg, jpg vagy png kiterjesztésű képet válasszon!';
                }
            } else {
                echo 'Válasszon képfájlt!';
            }
        }
    } else {
        echo "$email - This is not a valid email!";
    }
} else {
    
     $fname == null? $emptyFields[] = "<b>fname</b>":$fname;
     $lname == null? $emptyFields[] = "<b>lname</b>":$lname;
     $email == null? $emptyFields[] = "<b>email</b>":$email;
     $password == null? $emptyFields[] = "<b>password</b>":$password;
    echo implode(", ", $emptyFields).' input field are requierd!';
}