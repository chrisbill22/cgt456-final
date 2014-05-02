
  $("#facebox").overlay({
    top: 260, // custom top position
    mask: { 
		color: '#fff', // transparent mask color
    	loadSpeed: 200, 
	    opacity: 0.5 //transparency
    },
 
    closeOnClick: false, // disable for modal dialog-type of overlays
    load: true  // load it immediately after the construction
    
    });