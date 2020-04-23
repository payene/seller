$(document).ready(function(){

	$('#stat_by_clt_form').submit(function(e){
		e.preventDefault();

		let target_url = window.location.href; 

  	 	let  form = new FormData(document.getElementById('stat_by_clt_form'));

  	// 	$( "#stat_by_clt_table_container" ).load(target_url+" #stat_by_clt_table", form ); 

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
		    	new_node = document.getElementById(data.);
		    	old_node = document.getElementById('stat_by_clt_table');

		    	document.getElementById('stat_by_clt_table_container').replaceChild(new_node,old_node);

		    },
		    error : function(xhr)
		    {

		    }

		 })
		
	});

	$('#stat_by_article_form').submit(function(e){
		e.preventDefault();

		let target_url = window.location.href;  

  	 	let  form = new FormData(document.getElementById('stat_by_article_form'));

  	 	$( "#stat_by_article_table_container" ).load(target_url+" #stat_by_article_table", form ); 
	});

});