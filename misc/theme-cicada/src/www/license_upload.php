<?php
require_once("guiconfig.inc");

// Blocking services and Resetting cache
$block_services="/usr/local/bin/python3 /usr/local/opnsense/service/service_set.py block";
$block_cache="rm /tmp/opnsense_menu_cache.xml";
shell_exec($block_services);
shell_exec($block_cache);

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_upload'])) {
    $target_dir = "/conf/"; // Directory where the file will be uploaded
    $target_file = $target_dir . basename($_FILES["file_upload"]["name"]);
    echo $target_file;
    $uploadOk = 1;
    $licFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script> alert('Sorry, file already exists.'); </script>";
        $uploadOk = 0;
    }

     //Check if the file is .lic or not
     if($licFileType != 'lic'){
         echo "<script> alert('This is not .lic file') </script>";
         $uploadOk = 0;
     }

    // Check file size
    if ($_FILES["file_upload"]["size"] > 500000) {
        echo "<script> alert('Sorry, your file is too large'); </script>";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script> alert('Sorry, your file was not uploaded.'); </script>";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
            $command = "/usr/local/bin/python3 /usr/local/opnsense/service/upload_data.py -c 'from upload_data import upload_license; upload_license()'";
            $output = shell_exec($command);

            if($output == True){
                echo "<script> alert('Uploaded Successfully'); </script>";
                $activate="/usr/local/bin/python3 /usr/local/opnsense/service/block_services.py activate";
                shell_exec($activate);
                $ResetCache="rm /tmp/opnsense_menu_cache.xml";
                shell_exec($ResetCache);
            }else{
                echo "<script> alert('Invalid file'); </script>";
            }
        } else {
            echo "<script> alert('Not uploaded'); </script>";
        }
    }
}

include("head.inc");

?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        // File was successfully uploaded
        $status ='<span class="text-successful">File uploaded successfully.</span>' ;
    } else {
        // An error occurred during the file upload
        $status = '<span class="text-danger">File upload failed.</span>'. $_FILES['fileToUpload']['error'];
    }
} 
?>

<?php include("fbegin.inc"); ?>

<?php  $command = "/usr/local/bin/python3 /usr/local/opnsense/service/service_status.py show_lic";

$output = shell_exec($command);

$real_names= array(
"Demo" => "Demo License",
"App_lic"=> "Appliance License",
"IPS/IDS" => "IDS",
"Zenarmor"=>"Application and URL Filter",
"URLF"=>"URL Filter",
"Sndbx"=>"Sandboxing",
"Avirus"=>"Antivirus",
"ASpam"=>"Antispam",
"Email"=>"Email Security",
"WAF"=>"WAF",
"DNS"=>"DNS",
"SLB"=>"Server Load Balancer");

if (!empty($output)) {
    $array = json_decode($output,true);
    // echo $array;
}

                if (empty($output)) {

                    $name= "N/A";
                    $type= "N/A";
                    $start="N/A";
                    $end="N/A";

                } else {
                    $name= $array["lic_type"];
                    $type= $array["lic_service"];
                    $start=$array["start_date"];
                    $end=$array["end_date"];
                }
                
                ?>
    <section class="col-xs-11">
            <ul class="nav nav-tabs" data-tabs="tabs" id="maintabs">
                <li class="active"><a data-toggle="tab" id="license_tab" href="#license">Show License</a></li>
                <li><a data-toggle="tab" id="upload_tab" href="#upload">Upload License</a></li>
            </ul>
            <div class="tab-content content-box" style="padding: 10px;">
                <div id="license" class="tab-pane fade in active">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <?=gettext("License Name");?>
                                </th>
                                <th>
                                    <?=gettext("Services");?>
                                </th>
                                <th>
                                    <?=gettext("License Start Date");?>
                                </th>
                                <th>
                                    <?=gettext("License End Date");?>
                                </th>
        
                            </tr>
        
                        </thead>
                        <tbody>
                        <?php foreach ((array)$type as $item): ?>
                                <tr>
                                    <td><?=gettext($real_names[$name]) ?></td>
                                    <td><?=gettext($real_names[$item]) ?></td>
                                    <td><?=gettext($start) ?></td>
                                    <td><?=gettext($end) ?></td>                                
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
        
                    </table>
        
                </div>
            <div id="upload" class="tab-pane fade in tab-content content-box col-xs-12 __mb">
                <form method="POST" name="iform" id="iform" action="license_upload.php" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <td> <?=gettext("Upload Document"); ?></td>
                                <td style="display: flex; flex-direction: column;">
                                    <input name="file_upload" type="file" size="20" accept=".lic" />
                                    <span style="margin-top: 5px"><?php echo $status; ?></span>
                                </td>
                                <td><button name='upload' type="submit" class="btn btn-xs btn-primary" onClick=""><span>Upload</span></button></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
</section>

<!-- <section class="page-content-main">
    <div class="container-fluid">
        <form method="POST" name="iform" id="iform" action="license_upload.php" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <td> <?=gettext("Upload Document"); ?></td>
                        <td style="display: flex; flex-direction: column;">
                            <input name="file_upload" type="file" size="20" />
                            <span style="margin-top: 5px"><?php echo $status; ?></span>
                        </td>
                        <td><button name='upload' type="submit" class="btn btn-xs btn-primary" onClick=""><span>Upload</span></button></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</section> -->

<?php include("foot.inc"); ?>
