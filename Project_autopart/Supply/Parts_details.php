<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Part details</h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT PartNo, PartName, vendor.VendorId, VName, Price, SLoc FROM parts_info, vendor WHERE parts_info.vendorID = vendor.vendorID;";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Part Number</th>";
                                        echo "<th>Part Name</th>";
                                        echo "<th>Vendor Id</th>";
                                        echo "<th>Vendor Name</th>";
										echo "<th>Price</th>";
										echo "<th>Storage Location</th>";
										echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
										echo "<td>" . $row['PartNo'] . "</td>";
                                        echo "<td>" . $row['PartName'] . "</td>";
                                        echo "<td>" . $row['VendorId'] . "</td>";
                                        echo "<td>" . $row['VName'] . "</td>";
                                        echo "<td>" . $row['Price'] . "</td>";
                                        echo "<td>" . $row['SLoc'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                        <a href="http://localhost/project_autopart/Main.php" class="btn btn-success pull-right">Back to Main Menu</a>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>