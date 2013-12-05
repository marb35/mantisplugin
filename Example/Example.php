<?php

require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );
require ("bug_api.php"); 
//require ("bug_report.php");
class ExamplePlugin extends MantisPlugin {
    var $id;
    var $bug_id;
    function register() {
        $this->name = 'Example';    # Proper name of plugin
        $this->description = 'the first plugin for mantisbt';    # Short description of the plugin
        $this->page = '';           # Default plugin page

        $this->version = '1.0';     # Plugin version string
        $this->requires = array(    # Plugin dependencies, array of basename => version pairs
            'MantisCore' => '1.2.0',  #   Should always depend on an appropriate version of MantisBT
            );

        $this->author = 'Marco Ramirez';         # Author/team name
        $this->contact = '';        # Author/team e-mail address
        $this->url = '';            # Support webpage
    }

    function events() {
        return array(
            'EVENT_EXAMPLE_FOO' => EVENT_TYPE_EXECUTE, 
            'EVENT_REPORT_BUG' => EVENT_TYPE_EXECUTE,
            'EVENT_BUGNOTE_ADD' => EVENT_TYPE_EXECUTE,
        );
    }

    function hooks() {
        $regresa=array(
            'EVENT_EXAMPLE_FOO' => 'foo',
            'EVENT_MENU_MAIN' => 'example_menu',
            'EVENT_REPORT_BUG' => 'autorespuesta',
            'EVENT_BUGNOTE_ADD' => 'try_trello',
        );
        return $regresa;
    }

    function config() {
        return array(
            'foo_or_bar' => 'foo',
        );
    }

    function foo( $p_event ) {
        echo 'In method foo(). ';
    }

    function bar( $p_event, $p_chained_param ) {
        return str_replace( 'foo', 'bar', $p_chained_param );
    }

    function example_menu(){
        return array( '<a href="' . plugin_page( 'foo' ) . '">Menu FOO</a>', );
    }
    /**
     * Make an autoresponse when a new bug is reported
     */
    function autorespuesta(){
        $f_page_number=gpc_get_int( 'page_number', 1 );
    	$t_per_page = null;
    	$t_bug_count = null;
    	$t_page_count = null;
    	$rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, null, null, null, true );
        $t_row_count = count( $rows );
        echo $t_row_count;
        $t_bugslist = Array();
    	$t_users_handlers = Array();
    	$t_project_ids  = Array();
        for($i=0; $i < $t_row_count; $i++) {
    		array_push($t_bugslist, $rows[$i]->id );
    		$t_users_handlers[] = $rows[$i]->handler_id;
    		$t_project_ids[] = $rows[$i]->project_id;
        }
        $p_bug_id=$t_row_count;
        $arreglo1=bug_get_extended_row($p_bug_id);
        $repo_id=$arreglo1["reporter_id"];
        $name1 = user_get_realname($repo_id);
        $mensaje1="<pre>";
        $mensaje1.=lang_get( 'plugin_Example_autorespuesta2' ).$name1.",";
        $mensaje1.="\r".lang_get( 'plugin_Example_autorespuesta' );
        $mensaje1.="\rProcessMaker Support Team";
        $mensaje1.="<pre/>";
        if (bug_exists($p_bug_id)){
            bugnote_add($p_bug_id,$mensaje1,"2:00",false,0,"",1);    
        }
        
    }
    
    /**
    * Trigger an event when a new note is added to the bug ticket
    */
    function try_trello(){
        $p_bug_id=$_COOKIE["MANTIS_BUG_LIST_COOKIE"];
        $nuevo=explode(",",$p_bug_id);
        $nuevo_id=(int)$nuevo[0];
        $alerta="<script type='text/javascript'>";
        $alerta.="alert('Se introdujo una nueva nota');";
        $alerta.="</script>";
        $p_bug_id = $this->id;
        if(bug_exists( $nuevo_id )){
            echo $alerta;
        }
        else {
            echo "No. no. no!!!!!";
        }
        
    }
    
}