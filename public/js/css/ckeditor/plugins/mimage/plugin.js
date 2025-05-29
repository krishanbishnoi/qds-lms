/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'mimage',
{
	
	init: function( editor )
	{
		// Define an editor command that inserts a mimage. 
		
		editor.addCommand( 'insertMimage',
			{
				
				exec : function( editor )
				{    
					//var mimage = new Date();
					// Insert the mimage into the document.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#insertHtml
					//editor.insertHtml( 'Test: <em>' + mimage.toString() + '</em>' );
					CKEDITOR.tools.callFunction( ref );
					//alert("hi");
					
				}
			});
		// Create a toolbar button that executes the plugin command. 
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.html#addButton
		editor.ui.addButton( 'Mimage',
		{
			// Toolbar button tooltip.
			label: 'Select file from your computer',
			// Reference to the plugin command name.
			size : 38,
			command: 'insertMimage',
			// Button's icon file path.
			icon: this.path + 'images/image-icon.png'
		} );
	}
} );