
<!--<script src="/js/jquery-2.1.4.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js"></script>-->
<!--<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<div id="wrapper">
    <div id="main-container">
        <?php foreach ($streams as $stream):
            echo $stream['videoPlayer'];
        endforeach; ?>
    </div>
</div>
