
$(document).ready(function(){
	// console.log('loaded');
	//demande d'ajout d'article au panier
	$('#add_to_card_form').on('submit', function(e){
		 // alert('tfgd');
		 e.preventDefault();

		 if(!$('#form_article').val())
		 {
		 	alert("Vous devez choisir un article");
		 	return false;
		 }

		 if(!$('#form_qte').val() || !($('#form_qte').val()>0) )
		 {
		 	alert("Vous devez indiquer une quantité supérieure à 0");
		 	return false;
		 }

		 if(!confirm("Ajouter au panier ?"))
		 	return false;


		 let target_url = $('#add_to_card_form_url').val();  

  	 	 let  form = new FormData(document.getElementById('add_to_card_form'));


		 $.ajax({
		 	url : target_url,
		    method : "POST",
		    data : form,
		    async : true,
		    mimeType:"multipart/form-data",
	        contentType: false,
	        cache: false,
	        processData:false,
		    success : function(data)
		    {
		    	data = JSON.parse(data);

		    	alert(data.msg);

		    	$('#form_article').val(null);
		    	$('#form_qte').val(null);
		    	$('#artInCardNumber').val( data.artInCardNumber );
		    	$('#form_article').select2('data', null);
		    	// $('#save_vente_link').show();
		    	//$(".select2").html('').select2();

		    	refreshArticleList();
		    },
		    error : function(xhr)
		    {

		    }

		 })
		 

	});



	//vider le panier
	$('#destroy_card_link').on('click', function(e){
		e.preventDefault();
		if(!confirm("Vider le panier ?"))
		 	return false;
		destroyCard();
		$('#artInCardNumber').val( 0 );
	});

	//retirer un article du panier 
	$('.remove_from_card').on('click', function(e){
		e.preventDefault();
		if(!confirm("Retirer du panier ?"))
		 	return false;
		let target_url = $(this).attr('href');
		$.ajax({
		 	url : target_url,
		    method : "GET",
		    async : true,
	        contentType: false,
	        cache: false,
	        processData:false,
		    success : function(data)
		    {
		    	data = JSON.parse(data);

		    	$('#artInCardNumber').val( data.artInCardNumber );

		    	alert(data.msg);
		    	refreshArticleList();

				$('#save_vente_link').css('visibility','visible');
				$('#cltList').css('visibility','visible');
		    },
		    error : function(xhr)
		    {
		    	
		    }
		});
	});

	//enregistrer la vente 
	$('#save_vente_link').on('click', function(e){
		// console.log('cltList');
		e.preventDefault();
		var nbArt = $('#artInCardNumber').val();
		if(nbArt==0)
		{
			alert("Vous devez avoir au moins un article présent dans le panier pour continuer");
			return false;
		}
		if(!confirm("Enregistrer la vente? (Attention : opération irréversible) "))
		 	return false;
		$('#vente_view_form').show();
		$('#save_vente_link').css('visibility','hidden');
		$('#cltList').css('visibility','visible');
	});

	//validation de la vente
	$('#validerVenteBtn').on('click',function(e){
		// alert('clicked');
		e.preventDefault();

		var clt = $('#form_client').val(),
		    tel = $('#form_telephone').val();


		if($("#newCltZone").is(":visible"))
		{
			//s'assurer le tel est renseigné
			if(!tel)
			{
				alert("Vous devez renseigner le téléphone du client.");
				return false;
			}
		}
		else 
		{
			
			if(!clt)
			{
				alert("Vous devez sélectionner un client ou en créer un nouveau.");
				return false;
			}

		}

		if(!confirm("Valider la vente ?"))
			return false;

		let target_url = $('#nouvelleVenteFormUrl').val();  

  	 	let  form = new FormData(document.getElementById('vente_view_form'));

		$.ajax({
		 	url : target_url,
		    method : "POST",
		    data : form,
		    async : true,
		    mimeType:"multipart/form-data",
	        contentType: false,
	        cache: false,
	        processData:false,
		    success : function(data)
		    {
		    	data = JSON.parse(data);

		    	if(data.statut == "succes")
		    	{
		    		document.location.href= data.factureUri;
		    	}
		    	else if (data.statut == "erreur")
		    	{
		    		alert(data.msg);
		    	}
		    	else
		    	{
		    		alert("Gérer le statut "+data.statut);
		    	}

		    },
		    error : function(xhr)
		    {

		    }

		 })


	});

	$('#newCltBtn').click(function(e){

		if($("#vente_view_form").is(":hidden"))
		{
			alert("Vous ne pouvez faire cette opération qu'au moment d'enregistrer la vente");
			return false;
		}

		var clt = $('#form_client').val();
		if(clt)
		{
			alert("Vous ne pouvez effectuer cette opération si un client est sélectionné");
			return false;
		}

		if(!confirm("Créer un nouveau client ?"))
			return false;

		showNewCltForm();
	});

	$('#cancelNewCltBtn').on('click',function(e){
		if(!confirm("Annuler la creation du nouveau client ?"))
			return false;

		hideNewCltForm();
	});

	//modification du client choisi
	$('#form_client').on('select2:selecting', function (e) {
  		let old_clt = $('#form_client').val();

  		if(old_clt)
  		{
  			if(confirm("Vous êtes sur le point de changer de client. Les articles choisis selons vidés. Continuer ?"))
  			{

  				destroyCard();
  			}
  			else	
  				return false;
  		}

	});

	function showNewCltForm()
	{
		$('#newCltZone').css('display','block');
	}


	function hideNewCltForm()
	{
		$('#newCltZone').css('display','none');
	}

	

	function destroyCard()
	{

		 let target_url = $("#destroy_card_link").attr('href');

		 $.ajax({
		 	url : target_url,
		    method : "GET",
		    async : true,
	        contentType: false,
	        cache: false,
	        processData:false,
		    success : function(data)
		    {
		    	refreshArticleList();
		    },
		    error : function(xhr)
		    {
		    	
		    }

		 })
		   
	
	}


	function refreshArticleList()
	{
		document.location.reload(true);
		// let target_url = $('#ligne_vente_infos').data('refresh_url');

		// $( "#ligne_vente_infos_container" ).load(target_url+" #ligne_vente_infos" );
	}


})

