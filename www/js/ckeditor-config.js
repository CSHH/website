$(function() {
    CKEDITOR.replaceAll(function(textarea, config) {
        config.toolbarGroups = [
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'about', groups: [ 'about' ] }
        ];

        config.removeButtons = 'Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Scayt,Anchor,Table,HorizontalRule,SpecialChar,Source,Outdent,Indent,Blockquote,About,Styles';

        config.removePlugins = 'elementspath';

        config.resize_enabled = false;

        config.format_tags = 'p;h2;h3;h4;h5;h6';

        config.language = 'cs';

        return $(textarea).hasClass('editor') ? true : false;
    });
});
