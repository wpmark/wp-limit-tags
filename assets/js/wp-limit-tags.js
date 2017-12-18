( function( $ ) {
			
	/* set the maximum number of tags allowed - pulled from options */
	var maxtags = wplt.max_tags;
			
	/**
	 * function disabletags()
	 * this hides the tags button and disables the input when max tags is reached
	 */
	function disbaletags() {
		$( "input.newtag" ).prop('disabled', true );
		$( ".tagadd" ).css( 'visibility', 'hidden' );
		$( ".tagcloud-link" ).css( 'visibility', 'hidden' );
		$( ".the-tagcloud" ).css( 'visibility', 'hidden' );
	}
	
	/**
	 * function disabletagsbutton()
	 * this hides the tags button when max tags is reached
	 */
	function disabletagsbutton() {
		$( ".tagadd" ).css( 'visibility', 'hidden' );
	}
	
	/**
	 * function showaddtagsbutton()
	 * this shows the tags button and enables the input when below max tags
	 */
	function showaddtagsbutton() {
		$( "input.newtag" ).prop('disabled', false );
		$( ".tagadd" ).css( 'visibility', 'visible' );
		$( ".tagcloud-link" ).css( 'visibility', 'visible' );
		$( ".the-tagcloud" ).css( 'visibility', 'visible' );
	}
	
	function disableenter() {
		
	}
	
	/**
	 * here we are checking for DOM changes within the tagchecklist element
	 * we a change is detected we run either hide or show
	 * depending on whether the change is adding or removing an element
	 * we also count number of tags added to the input and disable if more than max tags
	 */
	$(document).ready( function() {
		
		$( '.tagchecklist' ).bind( "DOMSubtreeModified", function() {
			var count = $(".tagchecklist > span").length;
			if( count >= maxtags ) {
				disbaletags();
			} else {
				showaddtagsbutton();
			}
		});
		
		/* count tags as tying in input */
		$( "input.newtag" ).bind("keyup keypress", function(e) {
			
			/* get teh number of tags the user has entered into the input box */
			var tags = $( "input.newtag" ).val();					
			var inputtedtags = tags.split( ',' ).length;
			
			/* get the number of tags already added */
			var addedtags = $(".tagchecklist > span").length;
			
			/* work how many tags can be added now - based on the maxtags and the number already added */
			var tagsleft = maxtags - addedtags;
			
			/* if the tags inputted are greater than maxtags or greater than tagsleft to add */
			if( inputtedtags > maxtags || inputtedtags > tagsleft ) {
				disabletagsbutton();
				 e.preventDefault();
			} else {
				showaddtagsbutton();
			}
		});
		
	});
				
} )( jQuery );