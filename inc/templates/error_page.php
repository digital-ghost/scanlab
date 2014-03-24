<!DOCTYPE html>
<html>
    <head>
        <title>ERROR</title>
        <style>
            html,body {
                text-align: center;
                font-size: 30px;
                background-color: #111;
                color: #aaa;
                font-family: monospace;
            }            
            a, a:visited {
                color: #339;
            }
        </style>
        <script type="text/javascript" src="<?php echo REL_URL ?>js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo REL_URL ?>js/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo REL_URL ?>js/scanlab.js"></script>
    </head>
    <body>
        <div class="error">
            <h1>I am error</h1>
            <p><?php echo $error; ?></p>
             <a id="back" href="#">go back</a> | <a href="<?php echo REL_URL ?>">to index</a>
        </div>
    </body>
</html>
