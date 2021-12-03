<?php 
session_start();
if(!filter_var($_SESSION['unique_id'], FILTER_SANITIZE_NUMBER_INT)){
    header("location: login.php");
}

require_once './header.php'; 

?>
    <body>
        <div class="wrapper">
            <section class="users">
                <header>
                    <?php
                    include_once './ajax/config.php';
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if(mysqli_num_rows($sql) > 0){
                        $row = mysqli_fetch_assoc($sql);
                        if(empty($row['img']) || !file_exists("images/".$row['img']) )
                            $row['img'] = 'questionMark.svg';
//                            $row['img'] = 'unknown.jpg';
//                            $row['img'] = 'anonymous.svg';
                    }
                    ?>
                    <div class="content">
                        <img src="images/<?php echo $row['img']; ?>" alt="<?php echo $row['img']; ?>" title="<?php echo $row['img']; ?>" />
                        <div class="details">
                            <span><?php echo $row["fname"].' '.$row['lname'];  ?></span>
                            <p><?php echo $row['status']; ?></p>
                        </div>
                    </div>
                    <a href="#" class="logout">Logout</a>
                </header>
                <div class="search">
                    <span class="text">Select an user to start chat</span>
                    <input type="text" placeholder="Enter name to serach...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="users-list">
                    <a href="#">
                        <div class="content">
                            <img src="userImages/DSC04350.jpg" alt="" title="">
                            <div class="details">
                                <span>Coding Nepal</span>
                                <p>test message</p>
                            </div>
                        </div>
                        <div class="status-dot"><i class="fas fa-circle"></i></div>
                    </a>                    
                    <a href="#">
                        <div class="content">
                            <img src="userImages/DSC04350.jpg" alt="" title="">
                            <div class="details">
                                <span>Coding Nepal</span>
                                <p>test message</p>
                            </div>
                        </div>
                        <div class="status-dot"><i class="fas fa-circle"></i></div>
                    </a>                    
                </div> 
                <!--  userlist vége-->
            </section>
        </div>
        <script src="javascript/users.js"></script>
    </body>
</html>
