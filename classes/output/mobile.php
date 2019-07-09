<?php
namespace mod_groupselect\output;
 
defined('MOODLE_INTERNAL') || die();
require_once(dirname(dirname(__DIR__)).'/lib.php');
require_once(dirname(dirname(__DIR__)).'/locallib.php');

use context_module;
use moodle_url;

/**
 * The mod_hsuforum mobile app compatibility.
 *
 * @package	mod_hsuforum
 * @copyright  2018 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    public static function app_init(array $args) : array {
        global $CFG;
        return [
                'templates' => [],
                'javascript' => file_get_contents($CFG->dirroot . '/mod/groupselect/appjs/app_init.js'),
                'otherdata' => '',
                'files' => []
        ];
    }

	/**
	 * Returns the hsuforum discussion view for a given forum.
	 * Note use as much logic and functions from view.php as possible (view.php uses renderer.php and lib.php to build view)
	 * @param  array $args Arguments from tool_mobile_get_content WS
	 * @return array HTML, javascript and otherdata
	*/
    public static function groupselect_view($args) {
        global $OUTPUT, $USER, $DB, $PAGE;

        $args = (object) $args;

        $cm = get_coursemodule_from_id ( 'groupselect', $args->cmid, 0, false, MUST_EXIST );
        $groupselect = $DB->get_record ( 'groupselect', array (
            'id' => $cm->instance
        ), '*', MUST_EXIST );

        $course = $DB->get_record ( 'course', array ('id' => $cm->course), '*', MUST_EXIST );

        require_login ( $course, true, $cm );
        $context = context_module::instance ( $cm->id );


        $mygroups = groups_get_all_groups ( $course->id, $USER->id, $groupselect->targetgrouping, 'g.*' );

        $isopen = groupselect_is_open ( $groupselect );
        $groupmode = groups_get_activity_groupmode ( $cm, $course );
        $counts = groupselect_group_member_counts ( $cm, $groupselect->targetgrouping );
        $groups = groups_get_all_groups ( $course->id, 0, $groupselect->targetgrouping );
        $passwordgroups = groupselect_get_password_protected_groups ( $groupselect );
        $hidefullgroups = $groupselect->hidefullgroups;
        $exporturl = '';
        $ASSIGNROLE = 4;

        // Permissions
        $accessall = has_capability ( 'moodle/site:accessallgroups', $context );
        $viewfullnames = has_capability ( 'moodle/site:viewfullnames', $context );
        $canselect = (has_capability ( 'mod/groupselect:select', $context ) and is_enrolled ( $context ) and empty ( $mygroups ));
        $canunselect = (has_capability ( 'mod/groupselect:unselect', $context ) and is_enrolled ( $context ) and ! empty ( $mygroups ));
        $cancreate = ($groupselect->studentcancreate and has_capability ( 'mod/groupselect:create', $context ) and is_enrolled ( $context ) and empty ( $mygroups ));
        $canexport = (has_capability ( 'mod/groupselect:export', $context ) and count ( $groups ) > 0);
        $canassign = (has_capability ( 'mod/groupselect:assign', $context ) and $groupselect->assignteachers and (count(groupselect_get_context_members_by_role ( context_course::instance ( $course->id )->id, $ASSIGNROLE )) > 0));
        $canedit = ($groupselect->studentcansetdesc and $isopen);
        $cansetgroupname = ($groupselect->studentcansetgroupname);

        if ($course->id == SITEID) {
            $viewothers = has_capability ( 'moodle/site:viewparticipants', $context );
        } else {
            $viewothers = has_capability ( 'moodle/course:viewparticipants', $context );
        }

        $strgroup = get_string ( 'group' );
        $strgroupdesc = get_string ( 'groupdescription', 'group' );
        $strmembers = get_string ( 'memberslist', 'mod_groupselect' );
        $straction = get_string ( 'action', 'mod_groupselect' );
        $strcount = get_string ( 'membercount', 'mod_groupselect' );  

        // Group list
        foreach ( $groups as $group ) {

            $ismember = isset ( $mygroups [$group->id] );
            $usercount = isset ( $counts [$group->id] ) ? $counts [$group->id]->usercount : 0;
            $grpname = format_string ( $group->name, true, array (
                    'context' => $context
            ) );

            // Skips listing full groups if set
            if (! $ismember and $hidefullgroups and $groupselect->maxmembers === $usercount) {
                continue;
            }

            if (in_array ( $group->id, $passwordgroups )) {
                $group->password = true;
            } else {
                $group->password = false;
            }

            // Groupname and id
            $line = new \stdClass ();
            $line->groupname = $grpname;
            $line->groupid = $group->id;

            // Member count
            if ($groupselect->maxmembers) {
                $line->usercount = $usercount . '/' . $groupselect->maxmembers;
            } else {
                $line->usercount = $usercount;
            }

            if ($accessall) {
                $canseemembers = true;
            } else {
                if ($groupmode == SEPARATEGROUPS and ! $ismember) {
                    $canseemembers = false;
                } else {
                    $canseemembers = $viewothers;
                }
            }

            // Group members
            if ($canseemembers) {
                if ($members = groups_get_members ( $group->id )) {
                    $membernames = array ();
                    foreach ( $members as $member ) {
                        $pic = $OUTPUT->user_picture ( $member, array (
                                'courseid' => $course->id
                        ) );
                        if ($member->id == $USER->id) {
                            $membernames [] = fullname ( $member, $viewfullnames );
                        } else {
                            $membernames [] = fullname ( $member, $viewfullnames );
                        }
                    }
                    // Show assigned teacher, if exists, when enabled or when user is non-assigned teacher
                    if($groupselect->showassignedteacher or user_has_role_assignment($USER->id, $ASSIGNROLE, \context_course::instance ( $course->id )->id)) {
                                    $teacherid = null;
                    foreach ( $assigned_relation as $r ) {
                        if ($r->groupid === $group->id) {
                            $teacherid = $r->id;
                            break;
                        }
                    }
                    if ($teacherid) {
                        $teacher = null;
                        foreach ( $assigned_teachers as $a ) {
                            if ($a->id === $teacherid) {
                                $teacher = $a;
                                $break;
                            }
                        }
                        $pic = $OUTPUT->user_picture ( $teacher, array (
                                'courseid' => $course->id
                        ) );
                        if ($teacher->id == $USER->id) {
                            $membernames [] = fullname ( $teacher, $viewfullnames );
                        } else {
                            $membernames [] = fullname ( $teacher, $viewfullnames ) . ' (' . get_string ( 'assignedteacher', 'mod_groupselect' );
                        }
                                    }}
                    $line->members = implode ( ', ', $membernames );
                } else {
                    $line->members = '';
                }
            }

            // Icons
            $line->icons = '<div class="icons">';
            if ($groupselect->minmembers > $usercount) {
                $line->icons = $line->icons . $OUTPUT->pix_icon ( 'i/risk_xss', get_string ( 'minmembers_icon', 'mod_groupselect' ), null, array (
                        'align' => 'left'
                ) );
            }
            if ($group->password) {
                $line->icons = $line->icons . $OUTPUT->pix_icon ( 't/locked', get_string ( 'password', 'mod_groupselect' ), null, array (
                        'align' => 'right'
                ) );
            }
            $line->icons = $line->icons . '</div>';

            // Action buttons
            if ($isopen) {
                if (! $ismember and $canselect and $groupselect->maxmembers and $groupselect->maxmembers <= $usercount) {
                    $line->maxlimitreached = '<div class="maxlimitreached">' . get_string ( 'maxlimitreached', 'mod_groupselect' ) . '</div>'; // full - no more members
                    $actionpresent = true;
                } else if ($ismember and $canunselect) {
                    $line->maxlimitreached = $OUTPUT->single_button ( new moodle_url ( '/mod/groupselect/view.php', array (
                            'id' => $cm->id,
                            'unselect' => $group->id
                    ) ), get_string ( 'unselect', 'mod_groupselect', "") );
                    $actionpresent = true;
                } else if (! $ismember and $canselect) {
                    $line->maxlimitreached = $OUTPUT->single_button ( new moodle_url ( '/mod/groupselect/view.php', array (
                            'id' => $cm->id,
                            'select' => $group->id,
                            'group_password' => $group->password
                    ) ), get_string ( 'select', 'mod_groupselect', "") );
                    $actionpresent = true;
                } else {
                    $line->maxlimitreached = '';
                }
            }
                $data [] = $line;
        }

        //$data = json_decode (json_encode ($data), FALSE);
        // throw new \Exception(json_encode ($data), 1);
        

        $datagroups = array(
            'isopen' => $isopen,
            'title' => $groupselect->name,
            'cmid' => $cm->id,
            'groups' => json_encode (array_values($data))
        );

    	return array(
            'templates' => array(
                array(
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_groupselect/mobile_view_groupselect', $datagroups),
                ),
            ),
            'javascript' => '',
            'otherdata' => array(
                'groups' => json_encode(array_values($data))

            ),
            'files' => ''
        );
    }

    /**
     * Handle post discussion forms
     * @param array $args Arguments from tool_mobile_get_content WS
     * @return array HTML, javascript and otherdata
     */
    public static function add_group($args) {
        global $OUTPUT, $USER, $DB, $PAGE;
        return array(
            'templates' => array(
                array(
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_groupselect/mobile_view_add_new_group',array()),
                ),
            ),
            'javascript' => '',
            'otherdata' => '',
            'files' => ''
        );
    }

    /**
     * Handle post discussion forms
     * @param array $args Arguments from tool_mobile_get_content WS
     * @return array HTML, javascript and otherdata
     */
    public static function view_group($args) {
        global $OUTPUT, $USER, $DB, $PAGE;

        $args = (object)$args;

        $cm = get_coursemodule_from_id('groupselect', $args->cmid, 0, false, MUST_EXIST);
        $groupselect = $DB->get_record('groupselect', array (
            'id' => $cm->instance
        ), '*', MUST_EXIST );

        $course = $DB->get_record('course', array ('id' => $cm->course), '*', MUST_EXIST);

        require_login ($course, true, $cm);
        $context = context_module::instance ($cm->id);
        $accessall = has_capability('moodle/site:accessallgroups', $context);
        $viewfullnames = has_capability('moodle/site:viewfullnames', $context);

        $groupmode = groups_get_activity_groupmode($cm, $course);
        $group = groups_get_group($args->groupid);

        $data = array();
        $line = new \stdClass();

        if ($accessall) {
            $canseemembers = true;
        } else {
            if ($groupmode == SEPARATEGROUPS and !$ismember) {
                $canseemembers = false;
            } else {
                $canseemembers = $viewothers;
            }
        }

        if ($canseemembers) {
            if ($members = groups_get_members ($group->id)) {
                foreach ($members as $member) {
                    $member->fullname = fullname($member, $viewfullnames);
                    $memberpic = new \user_picture($member);
                    $memberpic->size = 100;
                    $member->userpicturesrc = $memberpic->get_url($PAGE)->out();
                    $data[] = $member;
                }
            }
        };

        return array(
            'templates' => array(
                array(
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_groupselect/mobile_view_show_group', array()),
                ),
            ),
            'javascript' => '',
            'otherdata' => array(
                'members' => json_encode(array_values($data))
            ),
            'files' => ''
        );
    }
}
