# Eannounce plugin for MantisBT:

## Introduction
This MantisBT plugin offers the ability to send an email notification to users of a project with a given access level.
You can also add manually some addresses.

## Installation
Copy the Eannounce plugin folder to your <mantis>/plugins folder, log in as a user who
can manage the plugins and click install next to the Eannounce plugin name. The plugin
is installed now.

## Configuration
Click to the plugin name in the plugin management page, it will redirect you to the 
configuration page. You can configure :
1. The access level needed to access this plugin ;
2. The access levels available to selection when you send an email.

## How to use
After you have install the Eannounce plugin it will add a new menu item under the MANAGE
menu called "Send email messages". Authorised users (see [Configuration](##Configuration) are able to open
this page and they can send messages to user groups in Mantis. 
More than one user group can be selected at the same time. All the users in the given user group(s) will receive 
the mail. You can manually add mail addresses in the Bcc or Cc field.

## Caution
Be careful to not flood the users with messages, use this feature when its really necessary.

## Licence
The Eannounce plugin for Mantis is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

The Eannounce plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with the Eannounce plugin.  If not, see <https://www.gnu.org/licenses/>.

