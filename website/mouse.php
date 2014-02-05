<!DOCTYPE html>
<html>
    <head>
        <title>Mouse tests</title>
        <style type="text/css">
            div.hover-wrapper > p.content {
                display: none;
            }
            div.hover-wrapper:hover > p.content {
                display: block;
            }
        </style>
    </head>
    <body>
        <h1>Mouse tests</h1>
        <p>Position: <span id="mouse-pos"></span></p>
        <div class="hover-wrapper">
            <h2 class="title">Hover me</h2>
            <p class="content">You see this text because of hover</p>
        </div>
        <script type="text/javascript">
            window.onmousemove = function (event) {
                event = event || window.event;
                document.getElementById('mouse-pos').innerHTML = event.clientX + '-' + event.clientY;
            };
        </script>
    </body>
</html>
