<?php session_start();?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto</title>
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
                        <h2 class="pull-left">What do you want to do?</h2>
                        
                    </div>
						<?php
							// define variables and set to empty values
							$DID= $job = "";
							$DID = $_SESSION['DID'];
							if ($_SERVER["REQUEST_METHOD"] == "POST") {
								  $job = test_input($_POST["job"]);
								}
							
							function test_input($data) {
								  $data = trim($data);
								  $data = stripslashes($data);
								  $data = htmlspecialchars($data);
								  return $data;
								}	
								
							if($DID=="D1"){
								echo "<a href='Admin/Employee_mgmt.php'>Manage Employees</a><br><br>";
								echo "<a href='Admin/Part_mgmt.php'>Manage Parts</a><br><br>";
								echo "<a href='Admin/Vendor_mgmt.php'>Manage Vendors</a><br><br>";
								echo "<a href='Admin/Vehicle_mgmt.php'>Manage Vehicles</a><br><br>";
								echo "<a href='Admin/Transaction.php'>View Transactions</a>";
							}
							
							if($DID=="D2"){
								echo "<a href='Supply/Receiving.php'>Receiving</a><br><br>";
								echo "<a href='Supply/Inventory.php'>Checking Inventory</a><br><br>";
								echo "<a href='Supply/Parts_details.php'>Part details</a><br><br>";
							}
							if($DID=="D3"){
								echo "<a href='Maint/Consume.php'>Consuming Part</a><br><br>";
								echo "<a href='Maint/Inventory.php'>Checking Inventory</a><br><br>";
								echo "<a href='Maint/Part.php'>Part Information</a><br><br>";

							}
							
							?>
							 <a href="http://localhost/project_autopart/login.php" class="btn btn-success pull-right">Logout</a>
	            </div>
            </div>        
        </div>
    </div>
</body>
</html>