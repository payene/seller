
$(document).ready(function(){
	
	//demande d'ajout d'article au panier
	$('#body_block_container').on('click','#add_to_card_form_submit_btn', function(e){
		 
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

		    	refreshArticleList();
		    },
		    error : function(xhr)
		    {

		    }

		 })
		 

	});



	//vider le panier
	$('#body_block_container').on('click', "#destroy_card_link", function(e){

		 e.preventDefault();

		 if(!confirm("Vider le panier ?"))
		 	return false;

		 destroyCard();


	});

	//retirer un article du panier 
	$('#body_block_container').on('click', ".remove_from_card", function(e){

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

		    	alert(data.msg);
		    	refreshArticleList();
		    },
		    error : function(xhr)
		    {
		    	
		    }

		 })
		   

	});

	//enregistrer la vente 
	$('#body_block_container').on('click', "#save_vente_link", function(e){

		 e.preventDefault();

		 if(!confirm("Enregistrer la vente? (Attention : opération irréversible) "))
		 	return false;

		 $('#vente_view_form').css('display','block');
		   

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

/*
	//enregistrer la vente 
	$('#body_block_container').on('click', "#save_vente_link", function(e){

		 e.preventDefault();

		 if(!confirm("Enregistrer la vente? (Attention : opération irréversible) "))
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

		    	alert(data.msg);
		    	refreshArticleList();

		    },
		    error : function(xhr)
		    {
		    	
		    }

		 })
		   

	});

*/
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
		let target_url = $('#ligne_vente_infos').data('refresh_url');

		$( "#ligne_vente_infos_container" ).load(target_url+" #ligne_vente_infos" );
	}


})

