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
 * The mod_groupselect mobile app compatibility.
 *
 * @package    mod_groupselect
 * @copyright  2018 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$addons = array(
    "mod_groupselect" => array( // Plugin identifier
        'handlers' => array( // Different places where the plugin will display content.
            'coursegroupselect' => array( // Handler unique name (alphanumeric).
                'displaydata' => array(
                    'icon' => '',
                    'class' => '',
                ),
                'delegate' => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the plugin)
                'method' => 'groupselect_view', // Main function in \mod_groupselect\output\mobile
                'init' => 'app_init', // Function that will run on runtime of the app
            )
        ),
        'lang' => array( // Language strings that are used in all the handlers. Can add more as required.
            array('groupselect'),
        ),
    )
);
