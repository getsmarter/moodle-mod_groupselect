<?php

$string['action'] = 'Action';
$string['assigngroup'] = 'Assign non-editing teachers to groups';
$string['assigngroup_confirm'] = 'This will assign non-editing teachers to groups, overriding possible previous assignment irreversibly. Are you sure?';
$string['assigngroup_help'] = 'If set, enables a button which assigns non-editing teachers to groups. Assigned non-editing teachers are not group members, '
        . 'but they show up in export file and in the main view (if set). Useful if course uses assistants to handle groups.';
$string['assignedteacher'] = 'Assigned non-editing teacher';
$string['cannotselectclosed'] = 'You can not become group member any more.';
$string['cannotselectmaxed'] = 'You can not join group {$a} - maximum number of members reached.';
$string['cannotselectnocap'] = 'You are not allowed to select group.';
$string['cannotselectnoenrol'] = 'You need to be enrolled into course in order to become a group member.';
$string['creategroup'] = 'Create a new group';
$string['cannotunselectclosed'] = 'You can not leave group any more';
$string['deleteemptygroups'] = 'Delete group when last student leaves';
$string['deleteemptygroups_help'] = 'If set, automatically deletes group when last student leaves from it';
$string['description'] = 'Group description';
$string['edittooltip'] = 'Click to edit';
$string['export'] = 'Create a download link for group data file (CSV)';
$string['export_download'] = 'Download CSV-file';
$string['fromallgroups'] = 'All groups';
$string['globalpassword_help'] = 'Set a global password for group joining. Overrides student set passwords.';
$string['groupid'] = 'Group ID';
$string['groupformation:addinstance'] = 'Add a new group self-selection';
$string['groupformation:assign'] = 'Allow to assign teachers to groups';
$string['groupformation:create'] = 'Allow creating of group';
$string['groupformation:select'] = 'Allow becoming of group member';
$string['groupformation:unselect'] = 'Allow leaving of group';
$string['hidefullgroups'] = 'Hide full groups from the main view';
$string['hidefullgroups_help'] = 'If set, hides all groups, which have reached max member count, from the main list view.'
        . ' (excluding user\'s own group). May be useful if the activity has lots of groups.';
$string['incorrectpassword'] = 'Incorrect password';
$string['managegroups'] = 'Manage groups';
$string['maxcharlenreached'] = 'Maximum character number reached';
$string['maxlimitreached'] = 'Maximum number reached';
$string['maxmembers'] = 'Max members per group';
$string['maxmembers_help'] = 'Maximum number of members per group, 0 for unlimited.';
$string['member'] = 'Member';
$string['membercount'] = 'Count';
$string['membershidden'] = 'Member list not available';
$string['memberslist'] = 'Members';
$string['minmembers'] = 'Min members per group';
$string['minmembers_help'] = 'Minimum number of members per group. Adds notifications for members of groups which are'
        . ' under this limit. Default is 0 (disabled).';
$string['minmembers_icon'] = 'Group has less members than required';
$string['minmembers_notification'] = 'Your group has less members than required! Minimum is {$a}.';
$string['modulename'] = 'Group self-formation';
$string['modulename_help'] = <<<'END_OF_HELP'
<p>Lets students create and select groups. Features:
</p>

<ul>
<li>Students can create groups, give them a description and set them password protected, if wanted</li>
<li>Students can select and join groups</li>
<li>Non-editing teachers may be assigned to groups</li>
<li>Teacher can export course group list as a csv-file</li>
<li>Full compatibility with basic Moodle groups: groups may be created by other means if needed, supports group
        assignment submissions etc.</li>
</ul>

<p>Currently in alpha stage, any feedback would be appreciated! (pirkka.pyykkonen@tut.fi)</p>
END_OF_HELP;
$string['modulenameplural'] = 'Group self-formations';
$string['nogroups'] = 'No groups available to select from, sorry.';
$string['notavailableanymore'] = 'Group selection is not available anymore, sorry (since {$a}).';
$string['notavailableyet'] = 'Group selection will be available on {$a}.';
$string['ok'] = 'OK';
$string['password'] = 'Requires password';
$string['pluginadministration'] = 'Module administration';
$string['pluginname'] = 'Group self-formation';
$string['saving'] = 'Saving...';
$string['selectconfirm'] = 'Do you really want to become member of the group <em>{$a}</em>?';
$string['showassignedteacher'] = 'Show assigned teachers';
$string['showassignedteacher_help'] = 'If set, assigned teachers will show up in group members. Useful if students need to know their assigned teacher';
$string['studentcancreate'] = 'Students can create groups';
$string['studentcancreate_help'] = 'If set, students without group (in selected grouping) can create groups';
$string['studentcansetdesc'] = 'Students can set and edit group description';
$string['studentcansetdesc_help'] = 'If set, student can set a a group description when creating one and group members can edit it';
$string['unselectconfirm'] = 'Do you really want to leave the group <em>{$a}</em>?';
$string['unselect'] = 'Leave group {$a}';
$string['select'] = 'Become member of {$a}';
$string['targetgrouping'] = 'Select groups from grouping';
$string['timeavailable'] = 'Open from';
$string['timedue'] = 'Open until';