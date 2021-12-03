<?php
session_start();
require_once './header.php';
$err = null;
if(filter_input(INPUT_POST, "adat", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)){
    require_once 'ajax/config.php';
    $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result = mysqli_query($conn,"SELECT lname FROM `users` WHERE `email`='{$email}';" );
            if( mysqli_num_rows($result) > 0){
                $err = "$email - már létezik ez az email cím!"; 
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
                        $destination = "images".DIRECTORY_SEPARATOR.$new_img_name;
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
                                $err = 'Sikertelen rögzítés\n'.$exc;
                            }



                            if($result){
                                //-- a felhasználó regisztrálása sikeres!
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if(mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id'];
                                    header("Location: users.php");
                                }
                            } else {
                                $err = 'A regisztráció sikertelen!';
                            }
                        } 
                    } else {
                        $err = 'Kérem jpeg, jpg vagy png kiterjesztésű képet válasszon!';
                    }
                } else {
                    $err = 'Válasszon képfájlt!';
                }
            }
        } else {
            $err = "$email - This is not a valid email!";
        }
    } else {

        $fname == null? $emptyFields[] = "<b>fname</b>":$fname;
        $lname == null? $emptyFields[] = "<b>lname</b>":$lname;
        $email == null? $emptyFields[] = "<b>email</b>":$email;
        $password == null? $emptyFields[] = "<b>password</b>":$password;
        $err = implode(", ", $emptyFields).' input field are requierd!';
    }
}
?>

    <body>
        <div class="wrapper">
            <section class="form signup">
                <header>Realtime Chat</header>
                <form action="#" method="POST" enctype="multipart/form-data">
                    <?php
                    if($err != null){
                        echo '<div class="error-txt">'.$err.'</div>';
                    }
                    ?>
                    
                    <div class="name_details">
                        <div class="field">
                            <label>First name</label>
                            <input type="text" name="fname" placeholder="First Name" required />
                        </div>
                        <div class="field">
                            <label>Last name</label>
                            <input type="text" name="lname" placeholder="Last Name" required />
                        </div>
                    </div>
                    <div class="field">
                        <label>Email Address</label>
                        <input type="text" name="email" placeholder="Enter Your Email" required />
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter new password" required />
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="field">
                        <label>Select Image</label>
                        <input type="file" name="image" required />
                    </div>
                    <div class="field button">
                        <button type="submit" name="adat" value="true">Continue to Chat</button>
                        <!--<input type="submit" value="Continue to Chat" />-->
                    </div>
                </form>
                <div class="link">Already signed up? <a href="login.php">Login now</a></div>  
            </section>
        </div>
        <script src="javascript/pass-show-hide.js"></script>
        <!--<script src="javascript/signup.js"></script>-->
    </body>
</html>
