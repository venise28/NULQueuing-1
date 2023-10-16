

<?php

@include '../database.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, ($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$email' AND password = '$password'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['email'] = $row['email'];
      header('location:dashboard.php');
   }else{
      $message[] = 'Incorrect email or password';
   }

}


?>

<html>
   <link rel="stylesheet" href="../index.css">

   <?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
   };
};
?>

<div class="form-container">
   <center>
        <form action="index.php" method="post">
            <h2>LOGIN</h2>
            <input type="email" name="email" placeholder="enter email" class="input-box" required>
            <input type="password" name="password" placeholder="enter password" class="input-box" required>
            <input type="submit" name="submit" value="Login" class="input-button">
        </form>
    </div>
</center>
</html>