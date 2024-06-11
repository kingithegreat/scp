<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
      <link rel="stylesheet" href="styles/styles.css">
  <body class="container">
      
      <?php
        // Enable error reporting
        error_reporting(E_ALL);
 
        // Display errors
        ini_set('display_errors', 1);
      
        
        include "connection.php";
        
        // initialise $row as empty array
        $row = [];
        
        //directed from index page record [update] button
        if(isset($_GET['update']))
        {
            $id = $_GET['update'];
            // based on id select appropriate record from db
            $recordID = $connection->prepare("select * from scp where ID = ?");
            
            if(!$recordID)
            {
                echo "<div class='alert alert-danger p-3 m-2'>Error preparing recored for updating.</div>";
                exit;
            }
            
            $recordID->bind_param("i", $id);
            
            if($recordID->execute())
            {
                echo "<div class='alert alert-warning p-3 m-2'>Record ready for updating.</div>";
                $temp = $recordID->get_result();
                $row = $temp->fetch_assoc();
            }
            else
            {
                echo "<div class='alert alert-danger p-3 m-2'>Error: {$recordID->error}</div>";
            }
        }
        
        
        if(isset($_POST['update']))
        {
            // Write a prepare statemnet to insert data
            $update = $connection->prepare("update scp set Item=?, Class=?, Description=?, Containment=?, Image=? where ID=?");
        
            $update->bind_param("sssssi",$_POST['Item'], $_POST['Class'], $_POST['Description'], $_POST['Containment'], $_POST['Image'], $_POST['ID']);
            
            if($update->execute())
        {
            echo "<div class='alert alert-success p-3 m-2'>Record updated successfully</div>";
        }
        else
        {
            echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
        }
        }
        
        
      ?>
      
      
    <h1>Update record</h1>
    
    <p><a href="index.php" class="btn btn-dark">Back to index page.</a></p>
    
    <form method="post" action="update.php" class="form-group">
        <input type="hidden" name="ID" value="<?php echo isset($row['ID']) ? $row['ID'] : '' ; ?>">
        <label>Scp Item:</label>
        <br>
        <input type="text" name="Item" placeholdoer="Item..." class="form-control" value="<?php echo isset($row['Item']) ? $row['Item'] : '' ; ?>">
        <br><br>
        
        <label>Class:</label>
        <br>
        <input type="text" name="Class" placeholdoer="Class..." class="form-control"value="<?php echo isset($row['Class']) ? $row['Class'] : '' ; ?>">
        <br><br>
        
        <label>SCP Description:</label>
        <br>
        <textarea name="Description" class="form-control" ><?php echo isset($row['Description']) ? $row['Description'] : '' ; ?></textarea>
        <br><br>
        
        <label>SCP Containment:</label>
        <br>
        <textarea name="Containment" class="form-control" ><?php echo isset($row['Containment']) ? $row['Containment'] : '' ; ?></textarea>
        <br><br>
        
        <label>Image:</label>
        <br>
        <input type="text" name="Image" placeholdoer="images/name_of_image.png" class="form-control"value="<?php echo isset($row['Image']) ? $row['Image'] : '' ; ?>">
        <br><br>
        
        <input type="submit" name="update" class="btn btn-primary">
        
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>