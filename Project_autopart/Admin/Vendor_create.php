<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values

$VendorID = $VName = $City = "";
$VendorID_err = $VName_err = $City_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate VendorID
    $input_VendorID = trim($_POST["VendorID"]);
    if(empty($input_VendorID)){
        $VendorID_err = "Please enter a Vendor ID.";
    } elseif(strlen("$input_VendorID") == 4){
		$VendorID = $input_VendorID;
	} else{
        $VendorID_err = "Please enter exact 4 charactors for VendorID.";
    }
    
    // Validate VName
    $input_VName = trim($_POST["VName"]);
    if(empty($input_VName)){
        $VName_err = "Please enter an Vendor Name.";     
    } else{
        $VName = $input_VName;
    }    
	
	// Validate City
    $input_City = trim($_POST["City"]);
    if(empty($input_City)){
        $City_err = "Please enter City.";     
    } else{
        $City = $input_City;
    }
    
    
    // Check input errors before inserting in database
    if(empty($VendorID_err) && empty($VName_err) && empty($City_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Vendor (VendorID, VName, City) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_VendorID, $param_VName, $param_City);
            
            // Set parameters
            $param_VendorID = $VendorID;
            $param_VName = $VName;
            $param_City = $City;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: Vendor_mgmt.php");
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
                        <h2>Adding New Vendor</h2>
                    </div>
                    <p>Please fill this form and submit to add new Vendor to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VendorID_err)) ? 'has-error' : ''; ?>">
                            <label>Vendor ID</label>
                            <input type="text" name="VendorID" class="form-control" value="<?php echo $VendorID; ?>">
                            <span class="help-block"><?php echo $VendorID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VName_err)) ? 'has-error' : ''; ?>">
                            <label>Vendor Name</label>
                            <input type="text" name="VName" class="form-control" value="<?php echo $VName; ?>">
                            <span class="help-block"><?php echo $VName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
                            <label>City</label>
                            <input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
                            <span class="help-block"><?php echo $City_err;?></span>
                        </div>
						<input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Vendor_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>