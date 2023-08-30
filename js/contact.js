// EZPZ Tooltip v1.0; Copyright (c) 2009 Mike Enriquez, http://theezpzway.com; Released under the MIT License
(function($){$.fn.ezpz_tooltip=function(options){var settings=$.extend({},$.fn.ezpz_tooltip.defaults,options);return this.each(function(){var content=$("#"+getContentId(this.id));var targetMousedOver=$(this).mouseover(function(){settings.beforeShow(content,$(this))}).mousemove(function(e){contentInfo=getElementDimensionsAndPosition(content);targetInfo=getElementDimensionsAndPosition($(this));contentInfo=$.fn.ezpz_tooltip.positions[settings.contentPosition](contentInfo,e.pageX,e.pageY,settings.offset,targetInfo);contentInfo=keepInWindow(contentInfo);content.css('top',contentInfo['top']);content.css('left',contentInfo['left']);settings.showContent(content)});if(settings.stayOnContent&&this.id!=""){$("#"+this.id+", #"+getContentId(this.id)).mouseover(function(){content.css('display','block')}).mouseout(function(){content.css('display','none');settings.afterHide()})}else{targetMousedOver.mouseout(function(){settings.hideContent(content);settings.afterHide()})}});function getContentId(targetId){if(settings.contentId==""){var name=targetId.split('-')[0];var id=targetId.split('-')[2];return name+'-content-'+id}else{return settings.contentId}};function getElementDimensionsAndPosition(element){var height=element.outerHeight(true);var width=element.outerWidth(true);var top=$(element).offset().top;var left=$(element).offset().left;var info=new Array();info['height']=height;info['width']=width;info['top']=top;info['left']=left;return info};function keepInWindow(contentInfo){var windowWidth=$(window).width();var windowTop=$(window).scrollTop();var output=new Array();output=contentInfo;if(contentInfo['top']<windowTop){output['top']=windowTop}if((contentInfo['left']+contentInfo['width'])>windowWidth){output['left']=windowWidth-contentInfo['width']}if(contentInfo['left']<0){output['left']=0}return output}};$.fn.ezpz_tooltip.positionContent=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY-offset-contentInfo['height'];contentInfo['left']=mouseX+offset;return contentInfo};$.fn.ezpz_tooltip.positions={aboveRightFollow:function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY-offset-contentInfo['height'];contentInfo['left']=mouseX+offset;return contentInfo}};$.fn.ezpz_tooltip.defaults={contentPosition:'aboveRightFollow',stayOnContent:false,offset:10,contentId:"",beforeShow:function(content){},showContent:function(content){content.show()},hideContent:function(content){content.hide()},afterHide:function(){}}})(jQuery);(function($){$.fn.ezpz_tooltip.positions.aboveFollow=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY-offset-contentInfo['height'];contentInfo['left']=mouseX-(contentInfo['width']/2);return contentInfo};$.fn.ezpz_tooltip.positions.rightFollow=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY-(contentInfo['height']/2);contentInfo['left']=mouseX+offset;return contentInfo};$.fn.ezpz_tooltip.positions.belowRightFollow=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY+offset;contentInfo['left']=mouseX+offset;return contentInfo};$.fn.ezpz_tooltip.positions.belowFollow=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=mouseY+offset;contentInfo['left']=mouseX-(contentInfo['width']/2);return contentInfo};$.fn.ezpz_tooltip.positions.aboveStatic=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=targetInfo['top']-offset-contentInfo['height'];contentInfo['left']=(targetInfo['left']+(targetInfo['width']/2))-(contentInfo['width']/2);return contentInfo};$.fn.ezpz_tooltip.positions.rightStatic=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=(targetInfo['top']+(targetInfo['height']/2))-(contentInfo['height']/2);contentInfo['left']=targetInfo['left']+targetInfo['width']+offset;return contentInfo};$.fn.ezpz_tooltip.positions.belowStatic=function(contentInfo,mouseX,mouseY,offset,targetInfo){contentInfo['top']=targetInfo['top']+targetInfo['height']+offset;contentInfo['left']=(targetInfo['left']+(targetInfo['width']/2))-(contentInfo['width']/2);return contentInfo}})(jQuery);

jQuery(document).ready(function() { 
	// fonts!
	Cufon.replace('h4', { fontFamily: 'FS Me' } );
	Cufon.replace('.thrive_title', { fontFamily: 'FS Me' } );
	Cufon.replace('.thrive_subtitle', { fontFamily: 'FS Me Light' });
	
	// bind 'myForm' and provide a simple callback function 
	jQuery( '#thrive_success_message' ).hide();
	jQuery( '#thrive_error_message' ).hide();
	jQuery( '.thrive_error_label' ).hide();

	jQuery( '.thrive_error_label' ).ezpz_tooltip({
		contentPosition: 'aboveStatic',
		stayOnContent: true,
		offset: 0
	});
	
	/* Fixes IE */
	jQuery( '.thrive_text_input' ).focus( function() {
		jQuery(this).addClass( 'thrive_text_input_focus' );
	} );

	/* Fixes IE */
	jQuery( '.thrive_text_input' ).blur( function() {
		jQuery(this).removeClass( 'thrive_text_input_focus' );
	} );
	
	jQuery('#thrive_contact_form').ajaxForm( { 
		'data' : { 'action' : 'thrive-contact-form' },
		'beforeSubmit': function( arr, jform, opts ) {
			var error = false;
			
			jQuery( '#thrive_success_message' ).hide();
			jQuery( '#thrive_error_message' ).hide();
			jQuery( '.thrive_error_label' ).hide();
	
			//jQuery( '#thrive_contact_form .thrive_error_label' ).remove();
			for ( i = 0; i < arr.length; i++ ) {
				if ( 'thrive_name' === arr[ i ].name &&  "" === arr[ i ].value ) {
					error = true;
					jQuery( '#thrive-target-1' ).show( 'fast' );
					continue;
				}
				
				if ( 'thrive_email' === arr[ i ].name ) {
					var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
					if ( ! pattern.test( arr[ i ].value ) ) {
						error = true;
						jQuery( '#thrive-target-2' ).show( 'fast' );
					}					
					continue;
				}
				
				if ( 'thrive_subject' === arr[ i ].name &&  "" === arr[ i ].value ) {
					error = true;
					jQuery( '#thrive-target-3' ).show( 'fast' );
					continue;
				}
				
				if ( 'thrive_message' === arr[ i ].name &&  "" === arr[ i ].value ) {
					error = true;
					jQuery( '#thrive-target-4' ).show( 'fast' );
				}
			}
			
			if ( error ) {
				jQuery( '#thrive_error_message' ).show();
			}
			
			return ! error;
		},
		'success': function( jsonString ) {
			var response = JSON.parse( jsonString );
			
			if ( response.success ) {
				jQuery( '#thrive_success_message' ).html( 'Thank you for your comment!' ).fadeIn( 'slow' );
			} else {
				jQuery( '#thrive_success_message' ).html( 'An error has occurred. Please try again later.' ).fadeIn( 'slow' );
			}
		},
		'resetForm' : true
	} ); 
	
	thrive_load_google_map();
}); 

function thrive_load_google_map() {
	if ( "OK" !== Thrive.map_status ) {
		return false;
	}
	
	var latlng = new google.maps.LatLng( Thrive.map_lat, Thrive.map_lng );
	
    var myOptions = {
      zoom: 18,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.SATELLITE
    };
	
    var my_map = new google.maps.Map( document.getElementById("map_canvas"), myOptions );
		
	var addr = Thrive.map_address.replace( / /g, '+' );
	var sll = Thrive.map_viewport_sw;
	var sspn = Thrive.map_viewport_ne;
	var ll = Thrive.map_lat + ',' + Thrive.map_lng;
	
	var print_url = "http://maps.google.com/maps?t=h&ll=" + ll + "&spn=0.024932,0.036478&z=17&pw=2";
	var drive_url = "http://maps.google.com/maps?f=d&source=s_q&hl=en&geocode=&q=" + addr + "&sll=" + sll + "&sspn=" + sspn + "&ie=UTF8&hq=&hnear=" + addr + "&t=h&ll=" + ll + "&spn=0.024932,0.036478&z=14&iwloc=A&daddr=" + addr;
	
	var contentString = '';
	
	contentString += '<div id="content">';
	if ( '' !== Thrive.map_icon ) {
		contentString += '<img src="' + Thrive.map_icon + '" class="thrive_map_icon" height="101" width="140" alt="Thrive" />';
	}
	contentString += '<h2 id="firstHeading" class="firstHeading thrive_contact_label_text">Thrive</h2>'+
		'<div class="thrive_clear"></div>'+
		'<p class="thrive_contact_info_text">'+
		'<a href="' + drive_url + '" target="_blank">Directions</a> <span class="thrive_contact_pipe">|</span> <a href="' + print_url + '" target="_blank">Print</a></p>'+
		'</div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	var marker = new google.maps.Marker({
		position: latlng, 
		map: my_map, 
		title:"Address of Thrive"
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(my_map,marker);
	});
	
	jQuery( '#thrive_print_map' ).attr( 'href', print_url );
}