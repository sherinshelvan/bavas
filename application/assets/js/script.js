(function($){
  window.readURL = function (input, preview_element = ".upload.image-preview"){
    preview_element = $(preview_element);
    if (input.files && input.files[0]) {
      const  mimeType = input.files[0]['type'];
      if(mimeType.split('/')[0] === 'image'){
        var reader = new FileReader();
        reader.onload = function(e) {
          preview_element.html('<img width="200px" src="'+e.target.result+'" alt="" />');
        }
        reader.readAsDataURL(input.files[0]);
      }
      else{
        preview_element.html("");
      }
    }
  }
	$(document).ready(function(){
    $('.sidenav').sidenav();
		$('.dropdown-trigger').dropdown();
		$('select').formSelect();
		$('.modal').modal();
    $('.collapsible').collapsible();
    $('.materialboxed').materialbox();
    $(document).on("click", ".delete.row-delete", function(event){
      var id = $(this).data("id");
      var delete_bu = $($(".delete.confirmation .delete.confirm"));
      delete_bu.attr("href", delete_bu.data('link')+id);
    });
  });
  /*$('div.message').append('<i class="material-icons close">close</i>');
  $(document).on("click", "div.message i.close", function(){
  	$(this).parents().find("div.card.notify-msg").slideUp("normal", function() { $(this).remove(); } );
  });
  $(document).on("click", ".upload.image-preview .close", function(){
    $(this).parent(".upload.image-preview").html("");
  });*/
})(jQuery);