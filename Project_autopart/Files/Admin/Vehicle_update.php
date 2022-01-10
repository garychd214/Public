<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Description = $VehicleID = $DID = "";
$Description_err = $VehicleID_err = $DID_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["VehicleID"]) && !empty($_POST["VehicleID"])){
    // Get hidden input value
    $VehicleID = $_POST["VehicleID"];
    
    // Validate Description
    $input_Description = trim($_POST["Description"]);
    if(empty($input_Description)){
        $Description_err = "Please enter the Description.";
    } else{
        $Description = $input_Description;
    }
    
    // Check input errors before inserting in database
    if(empty($Description_err)){
        // Prepare an update statement
        $sql = "UPDATE vehicle SET Description=?, ELName=?, DID=? WHERE VehicleID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_Description, $param_VehicleID);
            
            // Set parameters
            $param_Description = $Description;
            $param_VehicleID = $VehicleID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: Vehicle_mgmt.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["VehicleID"]) && !empty(trim($_GET["VehicleID"]))){
        // Get URL parameter
        $VehicleID =  trim($_GET["VehicleID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM vehicle WHERE VehicleID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_VehicleID);
            
            // Set parameters
            $param_VehicleID = $VehicleID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $Description = $row["Description"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: Vehicle_error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: Vehicle_error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Description_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <input type="text" name="Description" class="form-control" value="<?php echo $Description; ?>">
                            <span class="help-block"><?php echo $Description_err;?></span>
                        </div>

						<input type="hidden" name="VehicleID" value="<?php echo $VehicleID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Vehicle_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>