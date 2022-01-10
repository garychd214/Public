<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$PartName = $VendorID = $VehicleID = $Price = "";
$PartName_err = $VendorID_err = $VehicleID_err = $Price_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate PartName
    $input_PartName = trim($_POST["PartName"]);
    if(empty($input_PartName)){
        $PartName_err = "Please enter a Part Name.";
    } elseif(!filter_var($input_PartName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $PartName_err = "Please enter a valid PartName.";
    } else{
        $PartName = $input_PartName;
    }
    
    // Validate VendorID
    $input_VendorID = trim($_POST["VendorID"]);
    if(empty($input_VendorID)){
        $VendorID_err = "Please enter an Vendor Id.";     
    } else{
        $VendorID = $input_VendorID;
    }    
	
	// Validate VehicleID
    $input_VehicleID = trim($_POST["VehicleID"]);
    if(empty($input_VehicleID)){
        $VehicleID_err = "Please enter an Vehicle Id.";     
    } else{
        $VehicleID = $input_VehicleID;
    }
    
    // Validate Price
    $input_Price = trim($_POST["Price"]);
    if(empty($input_Price)){
        $Price_err = "Please enter the Price amount.";     
    } 
	elseif(!is_numeric($input_Price)){
        $Price_err = "Please enter a positive decimal value.";
    } 
	else{
        $Price = $input_Price;
    }
    
    // Check input errors before inserting in database
    if(empty($PartName_err) && empty($VendorID_err) && empty($VehicleID_err)&& empty($Price_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO parts_info (PartName, VendorID, VehicleID, Price) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssd", $param_PartName, $param_VendorID, $param_VehicleID, $param_Price);
            
            // Set parameters
            $param_PartName = $PartName;
            $param_VendorID = $VendorID;
            $param_VehicleID = $VehicleID;
			$param_Price = $Price;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: Part_mgmt.php");
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
                        <h2>Adding New Part</h2>
                    </div>
                    <p>Please fill this form and submit to add new part record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($PartName_err)) ? 'has-error' : ''; ?>">
                            <label>PartName</label>
                            <input type="text" name="PartName" class="form-control" value="<?php echo $PartName; ?>">
                            <span class="help-block"><?php echo $PartName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VendorID_err)) ? 'has-error' : ''; ?>">
                            <label>Vendor ID</label>
                            <input type="text" name="VendorID" class="form-control" value="<?php echo $VendorID; ?>">
                            <span class="help-block"><?php echo $VendorID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VehicleID_err)) ? 'has-error' : ''; ?>">
                            <label>Vehicle ID</label>
                            <input type="text" name="VehicleID" class="form-control" value="<?php echo $VehicleID; ?>">
                            <span class="help-block"><?php echo $VehicleID_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Price_err)) ? 'has-error' : ''; ?>">
                            <label>Price</label>
                            <input type="text" name="Price" class="form-control" value="<?php echo $Price; ?>">
                            <span class="help-block"><?php echo $Price_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Part_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>