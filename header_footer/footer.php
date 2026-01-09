<footer class="main-footer">
  <div class="pull-right hidden-xs"> <b>Version</b> 1.5.1 </div>
  <strong>Copyright &copy; 2023 <a href="https://enguae.com/" target="_blank">ENG GROUP</a>.</strong> All rights
  reserved. </footer>
<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
  })
</script>
<!---------------------------- jquery.validation Engine -------------->
<script src="../plugins/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../plugins/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!----------------------End of jquery.validation engine--------------->
<!-- bootstrap datepicker -->
<script type="text/javascript" src="../plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- noty -->
<script type="text/javascript" src="../plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topCenter.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topLeft.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topRight.js"></script>
<script type="text/javascript" src="../plugins/noty/themes/default.js"></script>
<!-- noty -->
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!--<script src="../dist/js/demo.js"></script>-->
<script type="text/javascript">
// Top Notificator
function ShowNotificator(add_class, the_text) {
    $('div#notificator').text(the_text).addClass(add_class).slideDown('slow').delay(2000).slideUp('slow', function () {
        $(this).removeClass(add_class).empty();
    }); 
}
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask();

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  })
</script>
<style type="text/css">
#notificator { display : none; left : 50%; margin-left : -100px; padding : 15px 25px; position : fixed; text-align : center; top : 20px; width : 200px; z-index : 5000; }
</style>
</body></html>