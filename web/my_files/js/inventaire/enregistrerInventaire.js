
$(document).ready(function(){
/*
	$('#form_article').on('select2:selecting', function (e) {
  		let oldVal = $('#form_article').val();

  		if(form_article)
  		{
  			if(! confirm("Vous êtes sur le point de changer d'article. Continuer ?"))	
  				return false;
  		}

	});
*/

	$('#enregistrerInventaireSubmitBtn').click(function(e){
		
		if(!$('#form_article').val())
		{
			alert("Veuillez choisir un article");
			return false;
		}

		if( !$('#form_qte').val())
		{
			alert("La quantité est obligatoire");
			return false;
		}

		if( $('#form_qte').val()==0 )
		{
			alert("La quantité doit être différente de 0");
			return false;
		}

	})

	$('#cloturerInventaireLink').click(function(e){
		if(!confirm("Clôturer l'inventaire ?"))
			return false;
	});
	

})

