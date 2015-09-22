# hots-logs-wp-plugin
My first WordPress Plugin.

A simple plugin to display Heroes of the Strom MMR leaderboards using data from www.hotslogs.com
##Installation
To add this plugin to your wordpress site:
* **Download the zip** from this repo.
* Open your WordPress admin page and navigate to **Plugins > Add New**
* Click the **Upload Plugin** button near the top
* Navigate to where you saved hots-logs-wp-plugin.zip and **install**
* **Activate** the plugin

##Setup & Usage
Once you have activated the plugin:
* Navigate to **Settings > Hots Logs**
* Add the HOTS Logs **player IDs** of the people you want to track
* **That's it!** The plugin will now track all the players in the database

##Removing Players
To remove a player from the database
* Navigate to **Settings > Hots Logs**
* Click the delete button next to the player you want to remove.

##Updating Data
Data from www.hotslogs.com is automatically updated by the plugin at the most once per hour.
This is currently triggered by wp_loaded and can slow page load while it's running. I'm looking for a way to fix this.
