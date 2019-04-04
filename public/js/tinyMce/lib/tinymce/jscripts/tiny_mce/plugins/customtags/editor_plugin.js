tinymce.PluginManager.add('mainsection', function(editor, url) {
    // Add a button that opens a window
    editor.addButton('mainSectionButton', {
        text: 'Main section',
        icon: false,
        onclick: function() {
        	editor.insertContent('<mainsection class="main-title">');
        }
    });

    // Adds a menu item to the tools menu
    editor.addMenuItem('mainSectionMenuItem', {
        text: 'main section element',
        context: 'tools',
        onclick: function() {
            editor.insertContent('<mainsection class="main-title">');
        }
    });
});