jQuery(document).ready(function($) { 
	$('#update-nav-menu').bind('click', function(e) {
		//alert ('inseert');
		if ( e.target && e.target.className && -1 != e.target.className.indexOf('item-edit')) {
			var inputclass = '';
			var inputvalue = '';
			//var inputvalue2 = ''; 
			$("input[value='"+xili_data.strings[0]+"'][type=text]").parent().parent().parent().each( function(){
				$(this).children('.description-thin,.field-url,.field-link-target,.field-description').each( function(){
					
					$(this).children('label').each( function(){
						//$(this).attr("style", "border:yellow solid 1px") ;
						if ( !$(this).parent().hasClass("field-link-target") ) { $(this).css("visibility", "hidden") } ;  // attr disabled pb avec remove...
						$(this).children('input').each( function(){
							if ( $(this).hasClass("edit-menu-item-attr-title") ) {
								inputvalue2 = $(this).attr("value") ;
							}
						});
					});
					
					//$(this).attr("style", "border:red solid 1px") ;
				});
				
				
				p = $('<p>').attr('class', 'description topo').html( '<strong>' + xili_data.strings[1] + '</strong><br />' + inputvalue2 + '<br />' + xili_data.strings[2] );
				
				if ( !$(this).children('p').hasClass('topo') )
				 { $(this).prepend(p); }
				
			});
			// pages
			$("input[value='"+xili_data.strings[3]+"'][type=text]").parent().parent().parent().each( function(){
				$(this).children('.description-thin,.field-url,.field-link-target,.field-description').each( function(){
					
					$(this).children('label').each( function(){
						//$(this).attr("style", "border:yellow solid 1px") ;
						if ( !$(this).parent().hasClass("field-link-target") ) { $(this).css("visibility", "hidden") } ;  // attr disabled pb avec remove...
						$(this).children('input').each( function(){
							if ( $(this).hasClass("edit-menu-item-attr-title") ) {
								inputvalue2 = $(this).attr("value") ;
							}
						});
					});
					
					//$(this).attr("style", "border:red solid 1px") ;
				});
				
				
				p = $('<p>').attr('class', 'description topo').html( '<strong>' + xili_data.strings[4] + '</strong><br /><br />' + inputvalue2 + '<br /><br />' + xili_data.strings[5] );
				
				if ( !$(this).children('p').hasClass('topo') )
				 { $(this).prepend(p); }
				
			});
			// menus
			$("input[value='"+xili_data.strings[6]+"'][type=text]").parent().parent().parent().each( function(){
				$(this).children('.description-thin,.field-url,.field-link-target,.field-description').each( function(){
					
					$(this).children('label').each( function(){
						//$(this).attr("style", "border:yellow solid 1px") ;
						if ( !$(this).parent().hasClass("field-link-target") ) { $(this).css("visibility", "hidden") } ;  // attr disabled pb avec remove...
						$(this).children('input').each( function(){
							if ( $(this).hasClass("edit-menu-item-attr-title") ) {
								inputvalue2 = $(this).attr("value") ;
							}
						});
						
						$(this).children('input').each( function(){
							if ( $(this).hasClass("edit-menu-item-classes") ) {
								inputvalue3 = $(this).attr("value") ;
								
							}
						});
						
					});
					
					//$(this).attr("style", "border:red solid 1px") ;
				});
				
				menuvalues = inputvalue3.split('-') ;
				menuvalues.shift();
				menulangs = inputvalue2.split('menu-wo-') ;
				menulangs.shift();
				valtable = "";
				
				var obj = {};
   				for (var index in menulangs){
        			obj[menulangs[index]] = menuvalues[index];
        			if ( menuvalues[index] != '0' ) {
        				
        				$.ajax({
	      			url: ajaxurl, // this is a variable that WordPress has already defined for us
	      			type: 'POST',
	      			async: false,
	      			cache: false,
	      			data: {
	         			action: 'get_menu_infos', // this is the name of our WP AJAX function that we'll set up next
	         			menu_id: menuvalues[index],
	      			},
	      			success : function(x) { valtable = valtable + x },
		 			// error : function(r) { valtable = valtable + 'error' + r }
	   			});
        				
						valtable = valtable + ' = [' + menulangs[index].replace('-', '') + '] <small>( ' + menuvalues[index] + ' )</small><br />';
        			}
   				}
   				
   				

				p = $('<p>').attr('class', 'description topo').html( '<strong>' + xili_data.strings[7] + '</strong><br /><br />' + valtable + '<br /><br />' + xili_data.strings[8] );
				
				if ( !$(this).children('p').hasClass('topo') )
				 { $(this).prepend(p); }
				
			});
		}
	});
});
