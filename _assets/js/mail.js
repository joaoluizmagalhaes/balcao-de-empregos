(function($) {

	$(document).on('ready', function(){
		$('.mailFunction').on('click', function(){

			var url = $(location). attr("href");
			var processo = $('.processo').html();
			var contratanteID = $('.contratante').val();

			var data = {
				action: 'cleanResponseFiles',
			    url	: url,
			    contratanteID: contratanteID,
			    processo: processo
			};

			$.ajax({
		        url:'http://localhost:82/freela/balcao-de-empregos/wp-admin/admin-ajax.php',
		        method: 'post',
		        data: data,
		        success:function(data) {
		            console.log(data);
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		});
	});

})( jQuery );