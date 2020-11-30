<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the version and other meta-info about the plugin
 *
 * Setting the $plugin->version to 0 prevents the plugin from being installed.
 * See https://docs.moodle.org/dev/version.php for more info.
 *
 * @package    mod_vidtrack
 * @copyright  2018 Pankaj Chejara <pankajchejara23@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');		
	global $DB;		
	$user=$_GET['user'];
	$course=$_GET['course'];
	$video=$_GET['video'];
	$state=$_GET['state'];
	$jsondata=$_GET['jsondata'];
	$contextinstanceid=$_GET['contextinstanceid'];

$record=new stdClass();
$record->user=$user;
$record->course=$course;
$record->video=$video;
$temp='';

switch($state){
	case -1:
		$temp='unstarted';//sin empezar
		break;
	case 0:
		$temp='ended';//finalizado
		break;
	case 1:
		$temp='playing';//en reproducción
		break;
	case 2:
		$temp='paused';//en pausa
		break;
	case 3:
		$temp='buffering';//almacenando en búfer
		break;
		
	case 5:
		$temp='cued';//video en cola
		break;
	default:
		$temp='unrecognized';
		break;
	
}
//$record->state=$temp;
//$record->time_occurred=date('Y-m-d H:i:s');
//$record->datos_json=$jsondata;

//$id=$DB->insert_record('youtube',$record,false);
/*
$log_manual=new stdClass();
$log_manual->other=$jsondata;
$log_manual->eventname='\mod_vidtrack\event\course_module_viewed';
$log_manual->component='mod_vidtrack';
$log_manual->action='viewed';
$log_manual->target='course_module';
$log_manual->objecttable='vidtrack';
$log_manual->objecttable='vidtrack';
$log_manual->objectid=1;
$log_manual->crud='r';
$log_manual->edulevel=2;
$log_manual->contextid=6504;
$log_manual->contextlevel=70;
$log_manual->contextinstanceid=1958;
$log_manual->userid=$user;
$log_manual->courseid=$course;
*/
//$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
//$cm = get_coursemodule_from_instance('vidtrack', $course, 0, false, MUST_EXIST);
$ctx = context_course::instance($course);
$contextmodule = context_module::instance($contextinstanceid);
if($contextinstanceid){
	$record2 = (object) array(
		 'edulevel' => 2,
		 'contextid' => $ctx->id,
		 'contextlevel' => $ctx->contextlevel,
		 'contextinstanceid' => $contextinstanceid,
		 'userid' => $user,
		 'courseid' => $course, 
		 'timecreated' => time(),
		 'eventname' =>'\mod_vidtrack\event\course_module_viewed',
		 'component' =>'mod_vidtrack',
		 'action' => 'viewed',
		 'target' =>'course_module',
		 'objecttable' =>'vidtrack',
		 'crud' => 'r',
		 'other' => $jsondata.json_encode($contextmodule),
	);
	$DB->insert_record('logstore_standard_log', $record2);
}

?>
