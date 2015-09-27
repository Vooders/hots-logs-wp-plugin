=== Plugin Name ===

Contributors:      Vooders
Plugin Name:       HOTS Logs Leaderboards
Plugin URI:        https://github.com/Vooders/hots-logs-wp-plugin/releases/tag/1.1.0
Tags:              Heroes of the Storm, hots, leaderboard, mmr, hotslogs
Author URI:        vooders.com
Author:            Kev 'Vooders' Wilson
Donate link:       
Requires at least: 3.0.1
Tested up to:      4.3.1
Stable tag:        1.1.0
Version:           1.1.0
License: GPLv2

Display Heroes of the Storm MMR leaderboards on your WordPress site. 

== Description ==
Track your frinds MMRs and display them in leaderboards with data from hotslogs.com

== Installation ==
= WordPress Installer = 
* Open your WordPress admin page and navigate to Plugins > Add New
* Click the Upload Plugin button near the top
* Navigate to where you saved `hots-logs-wp-plugin.zip` and install
* Activate the plugin

= Manual Install =
* Unzip the file
* Upload to `wp-content/plugins`
* Activate the plugin

== Screenshots ==
1. /assets/frontend.jpg
2. /assets/admin.jpg

== Changelog ==
= 1.1.0 =
* Now using hotslogs.com API
* Can now add player using BattleTags

== Frequently Asked Questions ==
= How do I add players? =
* Navigate to `Settings > Hots Logs`
* Enter the hotslogs player ID or BattleTag of the player you want to add.

= How do I remove a player? =
* Navigate to `Settings > Hots Logs`
* Click the delete button next to the person you want to remove

= How do I update the MMR data? =
* MMR data is automaticly updated from hotslogs.com.
* The plugin will check at most once an hour for new data.
* The plugin uses the hotlogs API system which does not update as often as the main site so some changes may take an hour or two to filter through the system.

== Upgrade Notice ==

* Faster fetch time for data.