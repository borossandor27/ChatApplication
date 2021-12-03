<?php 
session_start();
require_once './header.php'; 
$err = null;
if(filter_input(INPUT_POST, "adat", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)){
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
    if(!empty($email) && !empty($password)){
        require_once 'ajax/config.php';
        $sql = mysqli_query($conn, "SELECT unique_id FROM users WHERE email = '{$email}' AND password = '{$password}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $_SESSION['unique_id'] = $row['unique_id'];
            header("Location: users.php");
        } else {
            $err = 'Email or password is incorrect!';
        }
    } else {
        $err = 'All input fields are required!';
    }

}
?>
    <body>
        <div class="wrapper">
            <section class="form login">
                <header>Realtime Chat</header>
                <form action="#" autocomplete="off" method="POST">
                    <?php 
                    if($err != null){
                        echo '<div class="error-txt">'.$err.'</div>';
                    }
                    ?>
                    
                    <div class="field">
                        <label>Email Address</label>
                        <input type="text" name="email" placeholder="Enter Your Email" />
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="text" name="password" placeholder="Enter your password" />
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="field button">
                        <button type="submit" name="adat" value="true">Continue to Chat</button>
                        <!--<input type="submit" value="Continue to Chat" />-->
                    </div>
                </form>
                <div class="link">Not yet signed up? <a href="index.php">Signup now</a></div>  
            </section>
        </div>
    </body>
    <script src="javascript/pass-show-hide.js"></script>
    <!--<script src="javascript/login.js"></script>-->
    
</html>
