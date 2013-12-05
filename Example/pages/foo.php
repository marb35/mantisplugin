<?php
echo '<p>Here is a link to <a href="', plugin_page( 'foo' ), '">page foo</a>.</p>';
echo '<link rel="stylesheet" type="text/css" href="', plugin_file( 'foo.css' ), '"/>',
     '<p class="foo">';

event_signal( 'EVENT_EXAMPLE_FOO' );

$t_string = 'A sentence with the word "foo" in it.';

echo $t_new_string, '</p>';

echo '<p>RETURN <a href="'. config_get( 'default_home_page' ). '">Main page</a>.</p>';
		