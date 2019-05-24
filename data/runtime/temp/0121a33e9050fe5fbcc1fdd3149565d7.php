<?php /*a:1:{s:67:"D:\server\wnmp\wwwroot\Cms\application\admin\view\public\error.html";i:1556273981;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>跳转提示|操作失败</title>

    <link href="/static/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.css" rel="stylesheet">

    <link href="/static/admin/css/animate.css" rel="stylesheet">
    <link href="/static/admin/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">


<div class="middle-box text-center animated fadeInDown">
    <h1>:) </h1>

    <h3 class="font-bold ">操作失败</h3>

    <div class="error-desc">
        <p class="text-danger"><?php echo(strip_tags($msg));?></p>
        <p class="hidden">可以选择返回主界面: <br/><a href="#" class="btn btn-primary m-t">Dashboard</a></p>
    </div>

    <p class="jump">
        页面自动 <?php echo($url);?><a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
    </p>
</div>

<!-- Mainly scripts -->
<script src="/static/inspinia/js/jquery-2.1.1.js"></script>
<script src="/static/inspinia/js/bootstrap.min.js"></script>
<script type="text/javascript">
    (function() {
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        //执行javascript操作
        if (href.indexOf('void') !== -1) {
            //location.href = href;
            $('.jump').hide(); //不跳转
        } else {
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if (time <= 0) {
                    location.href = href; //TODO, 如果iframe还没有history时，会导致顶级history back;
                    clearInterval(interval);
                };
            }, 1000);
        }

    })();
</script>

</body>

</html>
