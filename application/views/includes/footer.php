

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>DH-WMS</b> Warehouse Management System
        </div>
        <strong> <a href="<?php echo base_url(); ?>">DH-WMS</a></strong> 
    </footer>
    

    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    
    <script type="text/javascript">
       var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        pageURL2 = pageURL.substring(0, pageURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL2+'"]');
            x.addClass('active');
            x.parent().addClass('active');
            x.parent().parent().addClass('active');
            x.parent().parent().parent().addClass('active');
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
            x.parent().parent().addClass('active');
            x.parent().parent().parent().addClass('active');
        var x= $('a[href="'+windowURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
            x.parent().parent().addClass('active');
            x.parent().parent().parent().addClass('active');
    </script>
  </body>
</html>