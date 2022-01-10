<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$PartName = $Vendorid = $Vehicleid = $price = "";
$PartName_err = $Vendorid_err = $Vehicleid_err = $price_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["PartNo"]) && !empty($_POST["PartNo"])){
    // Get hidden input value
    $PartNo = $_POST["PartNo"];
    
    // Validate name
    $input_PartName = trim($_POST["PartName"]);
    if(empty($input_PartName)){
        $PartName_err = "Please enter a Part Name.";
    } elseif(!filter_var($input_PartName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $PartName_err = "Please enter a valid Part Name.";
    } else{
        $PartName = $input_PartName;
    }
    
    // Validate VendorID
    $input_Vendorid = trim($_POST["Vendorid"]);
    if(empty($input_Vendorid)){
        $Vendorid_err = "Please enter an Vendor ID.";     
    } else{
        $Vendorid = $input_Vendorid;
    }
	// Validate Vehicleid
    $input_Vehicleid = trim($_POST["Vehicleid"]);
    if(empty($input_Vehicleid)){
        $Vehicleid_err = "Please enter an Vehicle ID.";     
    } else{
        $Vehicleid = $input_Vehicleid;
    }
    
    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price amount.";     
    } elseif(!is_numeric($input_price)){
        $price_err = "Please enter a positive decimal value.";
    } else{
        $price = $input_price;
    }
    
    // Check input errors before inserting in database
    if(empty($PartName_err) && empty($Vendorid_err) && empty($Vehicleid_err) && empty($price_err)){
        // Prepare an update statement
        $sql = "UPDATE parts_info SET PartName=?, VendorID=?, VehicleID=?, Price=? WHERE PartNo=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssdi", $param_PartName, $param_VendorID, $param_VehicleID, $param_Price, $param_PartNo);
            
            // Set parameters
            $param_PartName = $PartName;
            $param_VendorID = $Vendorid;
            $param_VehicleID = $VehicleID;
			$param_price = $price;
            $param_PartNo = $PartNo;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: part_mgmt.php");
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
    if(isset($_GET["PartNo"]) && !empty(trim($_GET["PartNo"]))){
        // Get URL parameter
        $PartNo =  trim($_GET["PartNo"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM parts_info WHERE PartNo = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_PartNo);
            
            // Set parameters
            $param_PartNo = $PartNo;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $PartName = $row["PartName"];
                    $Vendorid = $row["VendorID"];
					$Vehicleid = $row["VehicleID"];
                    $price = $row["Price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: Part_error.php");
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
        header("location: Part_error.php");
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
                        <div class="form-group <?php echo (!empty($PartName_err)) ? 'has-error' : ''; ?>">
                            <label>PartName</label>
                            <input type="text" name="PartName" class="form-control" value="<?php echo $PartName; ?>">
                            <span class="help-block"><?php echo $PartName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Vendorid_err)) ? 'has-error' : ''; ?>">
                            <label>Vendor ID</label>
                            <input type="text" name="Vendorid" class="form-control" value="<?php echo $Vendorid; ?>">
                            <span class="help-block"><?php echo $Vendorid_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Vehicleid_err)) ? 'has-error' : ''; ?>">
                            <label>Vehicle ID</label>
                            <input type="text" name="Vehicleid" class="form-control" value="<?php echo $Vehicleid; ?>">
                            <span class="help-block"><?php echo $Vehicleid_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="hidden" name="PartNo" value="<?php echo $PartNo; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Part_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>