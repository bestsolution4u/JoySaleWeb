$( "document" ).ready( function() {
	var $mainSidebar = $( "#sidebar-main" );

	$mainSidebar.simplerSidebar( {
		align: "left",
		attr: "sidebar-main",
		mask: {
			display: true
		},
		selectors: {
			trigger: "#sidebar-main-trigger",
			quitter: ".quitter"
		},
		animation: {
			easing: "easeOutQuint"
		},
		events: {
			callbacks: {
				animation: {
					freezePage: false
				}
			}
		}
	} );
} );
