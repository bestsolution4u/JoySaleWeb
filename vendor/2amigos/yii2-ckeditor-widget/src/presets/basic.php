<?php

/**
 *
 * basic preset returns the basic toolbar configuration set for CKEditor.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
return [
    'height' => 200,
    'toolbarGroups' => [
        ['name' => 'undo'],
        ['name' => 'paragraph', 'groups' => [ 'list', 'align' ]],
        ['name' => 'basicstyles', 'groups' => ['basicstyles','colors']],
        ['name' => 'links', 'groups' => ['links']],
        ['name' => 'others', 'groups' => ['others', 'about']],
    ],
    'removeButtons' => 'Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe',
    'removePlugins' => 'elementspath',
    'resize_enabled' => false
];
