<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<script type="text/javascript" >
	  	$(document).ready(function(){
	  		var toast = "<?=$this->session->flashdata('toast_message')?>";
	  		if(toast){
	  			M.toast({html: toast});
	  		}  		
	  	});
	</script>  
 	<script src="<?=base_url('application/assets/js/script.js')?>"></script>
  </body>
</html>