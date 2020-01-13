<?php
if (!(include "functions/database.php")) {
    echo "<a href='install/index.html'>Please Install the script first or make sure it is installed correctly.</a>";
    exit;
}
include "functions/count.php";

$data = $db->query("SELECT * FROM settings");
$info = $db->fetch_array($data);

$ads = $db->query("SELECT * FROM ads");
$ads_info = $db->fetch_array($ads);
?>
<!DOCTYPE html>
<html class="full" lang="en">

    <head>
		<base href="<?php echo $info['URL']; ?>/" />
        <meta charset="utf-8">
        <title><?php echo $info['name']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <meta name='twitter:url' content='<?php echo $info["URL"]; ?>'>
        <meta name='twitter:title' content='<?php echo $info['name']; ?>'>
        <meta property="og:title" content="<?php echo $info['name']; ?>">
        <?php
            $logo_url = $info['logo_url'];
            if (strpos($logo_url,";")) {
                $logo_url = explode(";",$logo_url);
                if (empty($logo_url[1])) {
                    $logo_url[1] = $logo_url[0];
                }
            } else {
                $logo_url = [$logo_url,$logo_url];
            }
            $logo_tag = "";
            if ($info['logo_type'] == 1) {
                $logo_tag = "<meta property=\"og:image\" content='$logo_url[0]'>";
            }
            echo $logo_tag;
        ?>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">

        <!-- Custom CSS for the Template -->
        <link href="css/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">


<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?3e535aa2c17907699537e42152e34484";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>



        <style>
            <?php echo $info['cstm-style']; ?>
        </style>
    </head>

    <body>
        <?php
            include "functions/menu.php";
        ?>
        <div class="container">
            <div class="row logo">
                <div class="col-lg-12" style="text-align:center">
                    <?php 
                        include "functions/logo.php";
                        include "functions/darkmode.php";
                    ?>
                </div>
            </div>
        </div>
        <div class="container animated fadeIn" style="max-width: 950px;">
            <form  action="create.php" method="POST" enctype="multipart/form-data">


                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group">
                            <input id="urlbox" class="form-control cz-shorten-input" name="longurl" value="http://" type="text" data-validation-error-msg=" ">
                            <span class="input-group-btn">
                                <button class="btn btn-large btn-primary cz-shorten-btn" type="submit" id="submit">缩短！</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 8px;">
                    <div class="col-lg-6">
                        <div class="input-group" style="margin-top: 2px;">
                            <span class="input-group-addon"><?php echo $info['URL']; ?>/</span>
                            <input type="text" id="cust"  data-validation="alphanumeric" data-validation-allowing="-_" data-validation-optional="true" data-validation-error-msg=" " name="cust" class=" span5 form-control" placeholder="Custom Link (OPTIONAL)">
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin-top: 2px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="text"  data-validation="alphanumeric" data-validation-allowing="-_" data-validation-optional="true" data-validation-error-msg=" " id="pass" name="pass" class="form-control" placeholder="Password (OPTIONAL)">
                        </div>
                    </div>
                </div>
            </form>
            <div class="row" style="">
                <div class="col-lg-12" style="text-align: center; margin-top: 20px;">
                    <?php echo '' . $ads_info['ad1'] . ''; ?>
                </div>
            </div>
            <div class="row" style="margin-top: 30px;" >
                <div class="col-lg-4 text-center">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2 class="newsize" style="font-weight:bolder;"> 总访问量 </h2> 
                            <h2 class="newsize" style="letter-spacing:1px;"><?php echo $num_rows3; ?></h2>
                        </div>
                    </div>            
                </div>
                <div class="col-lg-4 text-center">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2 class="newsize" style="font-weight:bolder;"> 总网址</h2>
                            <h2 class="newsize" style="letter-spacing:1px;"><?php echo $num_rows1; ?></h2>

                        </div>
                    </div>            
                </div>
                <div class="col-lg-4 text-center">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2 class="newsize" style="font-weight:bolder;"> 今天的网址</h2> 
                            <h2 class="newsize" style="letter-spacing:1px;"><?php echo $num_rows2; ?></h2>

                        </div>
                    </div>            
                </div>
            </div>
        </div>
        <!-- JavaScript -->
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/jquery.form-validator.min.js"></script>
        <script>
            $.validate({
                modules: 'security'
            });
	    </script>

    </body>

</html>
<?php $db->close_connection(); ?>