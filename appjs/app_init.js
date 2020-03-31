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
 * support for the mdl35+ mobile app
 * This file is the equivalent of
 * qtype/wordselect/classes/wordselect.ts in the core app
 * e.g.
 * https://github.com/moodlehq/moodlemobile2/blob/v3.5.0/src/addon/qtype/ddwtos/classes/ddwtos.ts
 */
 
(function (t) {

    /* Register a link handler to open mod/groupselect/view.php links anywhere in the app. */
    function AddonModGroupselectModuleLinkHandler() {
        t.CoreContentLinksModuleIndexHandler.call(this, t.CoreCourseHelperProvider, 'mmaModGroupselect', 'groupselect');

        this.pattern = /\/mod\/groupselect\/view\.php.*([\&\?]id=\d+)/;
        this.name = "AddonModGroupselectLinkHandler";
    }

    AddonModGroupselectModuleLinkHandler.prototype = Object.create(t.CoreContentLinksModuleIndexHandler.prototype);
    AddonModGroupselectModuleLinkHandler.prototype.constructor = AddonModGroupselectModuleLinkHandler;

    AddonModGroupselectModuleLinkHandler.prototype.getActions = function (siteIds, url, params, courseId) {
        return [{
            action: function() {
                let site = t.CoreSitesProvider.getCurrentSite();
                let navigation = site.appProvider.getRootNavController();

                const pageParams = {
                    cmid: parseInt(params.id, 10),
                };

                return Promise.resolve(t.CoreSitePluginsProvider.getContent('mod_groupselect', 'groupselect_view', pageParams)).then((contentResult) => {
                    console.log('PP1');
                    // Do some tests here on the contentResult if need be since we have all the data.
                    return Promise.resolve(navigation.push('CoreSitePluginsPluginPage', {
                        title: (contentResult['otherdata']['discussiontitle'] !== undefined) ? contentResult['otherdata']['discussiontitle'] : '',
                        component: 'mod_groupselect',
                        method: 'groupselect_view',
                        args: pageParams,
                        initResult: {},
                        jsData: {},
                    }));
                });
            }
        }];
    }

    t.CoreContentLinksDelegate.registerHandler(new AddonModGroupselectModuleLinkHandler());
    
})(this);
