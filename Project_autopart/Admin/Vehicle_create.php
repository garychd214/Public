<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values

$Description = $VehicleID = "";
$Description_err = $VehicleID_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Description
    $input_Description = trim($_POST["Description"]);
    if(empty($input_Description)){
        $Description_err = "Please enter a Description.";
    } elseif(!filter_var($input_Description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Description_err = "Please enter a valid Description.";
    } else{
        $Description = $input_Description;
    }
     
	
	// Validate VehicleID
    $input_VehicleID = trim($_POST["VehicleID"]);
    if(empty($input_VehicleID)){
        $VehicleID_err = "Please enter an Vehicle Id.";     
    } else{
		$VehicleID = $input_VehicleID;
    }
    
    
    // Check input errors before inserting in database
    if(empty($Description_err) && empty($VehicleID_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Vehicle (VehicleID, Description) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_VehicleID, $param_Description);
            
            // Set parameters
            $param_Description = $Description;
            $param_VehicleID = $VehicleID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Adding New Vehicle</h2>
                    </div>
                    <p>Please fill this form and submit to add new vehicle to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Description_err)) ? 'has-error' : ''; ?>">
                            <label>Vehicle ID</label>
                            <input type="text" name="VehicleID" class="form-control" value="<?php echo $VehicleID; ?>">
                            <span class="help-block"><?php echo $Description_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VehicleID_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <input type="text" name="Description" class="form-control" value="<?php echo $Description; ?>">
                            <span class="help-block"><?php echo $VehicleID_err;?></span>
                        </div>
						<input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Vehicle_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>