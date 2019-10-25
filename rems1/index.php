<?php include('buy.php') ?>
<?php 
 session_start(); 
 
  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
  }
?>

<?php
  // Create database connection
  $db = mysqli_connect("localhost", "root", "", "rems1");

  // Initialize message variable
  $msg = "";

  // If upload button is clicked ...
  if (isset($_POST['upload'])) {
    // Get image name
    $image = $_FILES['image']['name'];
    // Get text
    $description = mysqli_real_escape_string($db, $_POST['description']);
    //Get price
    $price = mysqli_real_escape_string($db, $_POST['price']);
    //Get location
    $location = mysqli_real_escape_string($db, $_POST['location']);
    // image file directory
    $target = "property2/".basename($image);
    //Insert the details to property2 table
    $sql = "INSERT INTO property2 (image, description, price, location ) VALUES ('$image', '$description', '$price', '$location')";
    // execute query
    //mysqli_query($db, $sql);
    //Insert the details to property1 table
    $get_email = $_SESSION['email'];
	  $sql1 = "INSERT INTO property1 (email) VALUES('$get_email')";
    mysqli_query($db, $sql1);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $msg = "Successful";
    }else{
      $msg = "Failed";
    }

    if(mysqli_query($db, $sql)){
      header('Location: index.php');
      exit;
    }

  }
  $result = mysqli_query($db, "SELECT * FROM property2");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">

</div>
<div>
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
        <h3>
          <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
          ?>
        </h3>
      </div>
    <?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
      <p><strong><?php echo $_SESSION['username']; ?></strong></p>
	  <ul>
      <li> <a href="index.php?logout='1'">logout</a></li>
	  </ul>
    <?php endif ?>
</div>

<form method="POST" action="index.php" enctype="multipart/form-data">
    <div id="content1">
      <?php
        while ($row = mysqli_fetch_array($result)) {
          echo "<div id='img_div'>";
            echo "<img src='property2/".$row['image']."' >";
            echo "<p><i>".$row['description']."</i></p>";
            echo "<p><b>".$row['price']."</b></p>";
            echo "<p>".$row['location']."</p>";
            echo "<input type = 'Submit' name = 'Buy' value='Buy'>";
          echo "</div>";
        }
      ?>

      <input type="hidden" name="size" value="1000000000">
      <div>
        <input type="file" name="image">
      </div>
      <div>
        <textarea 
          id="text" 
          cols="40" 
          rows="4" 
          name="description" 
          placeholder="Property description..."></textarea>
      </div>
      <div>
        <textarea 
          id="text1" 
          cols="40" 
          rows="1" 
          name="price" 
          placeholder="Enter price in Indian Rs"></textarea>
      </div>
      <div>
        <textarea 
          id="text2" 
          cols="40" 
          rows="1" 
          name="location" 
          placeholder="Enter the pincode"></textarea>
      </div>
      <div>
        <button type="submit" name="upload">Sell</button>
      </div>
    </div>
</form>
</body>
</html>