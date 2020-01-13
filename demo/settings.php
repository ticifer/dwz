<?php
session_start();
if (!$_SESSION["valid_user"]) {
    Header("Location: login.php");
}
?>
<?php
include "../functions/database.php";
$settings_updated = 0;
$error_updating = 0;
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if ($_GET["op"] == "general") {
        $webname = $_POST["web-name"];
        $url = $_POST["url"];
        $logo = $_POST["logo"] . ";" . $_POST["logo-dark"];
        $webmail = $_POST["webmail"];
        $logo_type = isset($_POST['logo_type']);
        $redirect = isset($_POST['redirect']);
        if ($logo_type) {
            $type = 1;
        } else {
            $type = 0;
        }
        if ($redirect) {
            $splash = 1;
        } else {
            $splash = 0;
        }
        if ($_SESSION["valid_user"]) {
            $action = "UPDATE settings SET name='$webname', url='$url', logo_url='$logo', email='$webmail', redirect = '$splash', logo_type = '$type'";
            $db->query($action);
            $settings_updated = 1;
        } else {
            $error_updating = 1;
        }
    } elseif ($_GET["op"] == "ads") {
        $ad1 = $db->escape_value($_POST["ad1"]);
        $ad2 = $db->escape_value($_POST["ad2"]);
        $ad3 = $db->escape_value($_POST["ad3"]);
        $ad4 = $db->escape_value($_POST["ad4"]);
        if ($_SESSION["valid_user"]) {
            $action = "UPDATE ads SET ad1='$ad1', ad2='$ad2', ad3='$ad3', ad4='$ad4'";
            $db->query($action);
            $settings_updated = 1;
        } else {
            $error_updating = 1;
        }
    } elseif ($_GET["op"] == "admin") {
        $user = $_POST["username"];
        $pass = $_POST["pass"];
        $pass_hash = $db->escape_value(password_hash($pass, PASSWORD_BCRYPT, ["cost"=>10]));
        if ($_SESSION["valid_user"]) {
            $action = "UPDATE settings SET admin_user='$user', admin_pass='$pass_hash'";
            $db->query($action);
            $settings_updated = 1;
        } else {
            $error_updating = 1;
        }
    } elseif ($_GET['op'] == "css") {
        $css = $db->escape_value($_POST['css']);
        if ($_SESSION["valid_user"]) {
            $action = "UPDATE settings SET `cstm-style`='$css'";
            $db->query($action);
            $settings_updated = 1;
        } else {
            $error_updating = 1;
        }
    }
}

$message = '';

if ($settings_updated) {
    $message = '<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="bs-example">
            <div class="alert alert-dismissable alert-info">
                <h5>设置更新</h5>
            </div>
        </div>
    </div>
</div><br>';
} elseif ($error_updating) {
    $message = '<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="bs-example">
            <div class="alert alert-dismissable alert-danger">
                <h5>抱歉，发生错误了. </h5>
            </div>
        </div>
    </div>
</div><br>';
}

$data = $db->query("SELECT * FROM settings");
$info = $db->fetch_array($data);
$logo_input = $info['logo_url'];
if (strpos($logo_input,";")) {
    $logo_input = explode(";",$logo_input);
} else {
    $logo_input = [$logo_input,""];
}
$ads = $db->query("SELECT * FROM ads");
$ads_info = $db->fetch_array($ads);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Settings - <?php echo $info['name']; ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../font/css/font-awesome.min.css">
        <!-- Page Specific CSS -->
        <link href="../css/bootstrap-switch.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/scrolling-nav.css" rel="stylesheet" type="text/css">
    </head>

    <!-- The #page-top ID is part of the scrolling feature - the data-spy and data-target are part of the built-in Bootstrap scrollspy function -->
    <body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

        <?php
            include "functions/menu.php"
        ?>

        <div id="services" class="services-section">
        <?php
            echo $message;
?>
            <div class="col-lg-8 col-lg-offset-2" style="margin-top:-20px;padding-bottom:100px;">
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                    <li class="active"><a href="#general" data-toggle="tab">General</a></li>
                    <li class=""><a href="#ads" data-toggle="tab">Ads</a></li>
                    <li class=""><a href="#custom-css" data-toggle="tab">Custom CSS</a></li>
                    <li class=""><a href="#admin" data-toggle="tab">Admin</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="general" >
                        <div class="col-lg-10 col-lg-offset-1">
                            <form role="form" action="?op=general" method="post" >

                                <div class="form-group">
                                    <label for="web-name">
                                        网站名称
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-edit form-control-feedback"></i>
                                        </span>
                                        <input type="text" value="<?php echo $info["name"]; ?>"  name="web-name" id="web-name" class="form-control">
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="url">
                                        URL <small> 例如，在末尾没有“ /”：https://0i.gs或http://0i.gs</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-link form-control-feedback"></i>
                                        </span>
                                        <input type="text" value="<?php echo $info["URL"]; ?>" name="url" id='url' class="form-control">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="InputName">
                                        Logo图片<small>如果关闭，则将名称用作徽标</small>
                                    </label>
                                    <div class="input-group">
<?php
if ($info["logo_type"] == 0) {
    ?>
                                            <input type="checkbox" name="logo_type" data-on-color="success" value="1" data-off-color="danger" >
    <?php
} elseif ($info["logo_type"] == 1) {
    ?>
                                            <input type="checkbox" name="logo_type" checked data-on-color="success" value="1"  data-off-color="danger" >
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="logo-url">
                                        Logo URL
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-link form-control-feedback"></i>
                                        </span>
                                        <input type="text" value="<?php echo $logo_input[0]; ?>" name="logo" id='logo-url'  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="logo-url">
                                        夜间模式徽标URL<small>如果与上面的徽标相同，请留空</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-link form-control-feedback"></i>
                                        </span>
                                        <input type="text" value="<?php echo $logo_input[1]; ?>" name="logo-dark" id='logo-url'  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="webmail">
                                        电子邮件<small>联系表格将发送到此电子邮件</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            @
                                        </span>
                                        <input type="text" value="<?php echo $info["email"]; ?>" id='webmail' name="webmail"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">
                                        跳转画面 <small> 如果关闭，则无需等待广告即可重定向</small>
                                    </label>
                                    <div class="input-group">
<?php if ($info["redirect"] == 0) {
    ?>
                                            <input type="checkbox" name="redirect" data-on-color="success" value="1" data-off-color="danger" >
<?php
} elseif ($info["redirect"] == 1) {
    ?>
                                            <input type="checkbox" name="redirect" checked data-on-color="success" value="1"  data-off-color="danger" >
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
                            </form>                
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ads">
                        <form role="form" action="?op=ads" method="post" >
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">广告 1 <small>主页</small></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="ad1" rows="5"><?php echo $ads_info['ad1']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:25px">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">广告 2 <small>错误页面</small></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="ad2"  rows="5"><?php echo $ads_info['ad2']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:25px">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">广告 3 <small>跳转画面（如果开启）</small></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="ad3"  rows="5"><?php echo $ads_info['ad3']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:25px">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">广告 4 <small>跳转画面（如果开启）</small></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="ad4"  rows="5"><?php echo $ads_info['ad4']; ?></textarea>
                                    </div>
                                </div>                
                            </div> 
                            <div class="row">
                                <div class="col-md-12 text-right" style="margin-top:25px">
                                    <button type="submit" class="btn btn-info btn-lg">提交</button>
                                </div>					
                            </div>	
                            <div style="margin-top:25px"> </div>	
                        </form>					
                    </div>
                    <div class="tab-pane fade" id="custom-css">
                        <form role="form" action="?op=css" method="post" >
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="css">自定义CSS</label>
                                    <div class="col-md-9">
                                        <textarea style="font-family:monospace" class="form-control" id="css" name="css" rows="5"><?php echo $info['cstm-style']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right" style="margin-top:25px">
                                    <button type="submit" class="btn btn-info btn-lg">提交</button>
                                </div>					
                            </div>	
                            <div style="margin-top:25px"> </div>	
                        </form>					
                    </div>
                    <div class="tab-pane fade" id="admin">
                        <div class="col-lg-10 col-lg-offset-1">
                            <form role="form" action="?op=admin" method="post" >

                                <div class="form-group">
                                    <label for="username">
                                        管理员用户名
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-user form-control-feedback"></i>
                                        </span>
                                        <input type="text" value="<?php echo $info["admin_user"]; ?>" name="username" id='username' class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password">
                                        管理员密码 
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-key form-control-feedback"></i>
                                        </span>
                                        <input type="password"  name="pass_confirmation" id="password"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">
                                        重新输入密码 
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa  fa-key form-control-feedback"></i>
                                        </span>
                                        <input type="password"  data-validation="confirmation" name="pass"  data-validation-error-msg=" "id="password_confirmation"  class="form-control">
                                    </div>
                                </div>

                                <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
                            </form>                
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Core JavaScript Files -->
        <script src="../js/jquery-1.10.2.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>
        <script src="../js/bootstrap-switch.min.js"></script>

        <script src="../js/jquery.form-validator.min.js"></script>
        <script>
            $.validate({
                modules: 'security'
            });

            $('input:checkbox').bootstrapSwitch();
        </script>


    </body>

</html>


