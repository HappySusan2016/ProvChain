Owncloud-Superlog
=================

Log users activities in order to debug and/or determine if mistake is machine or human side.

## DB structure ##
CREATE TABLE IF NOT EXISTS `oc_superlog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL DEFAULT ' ',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `protocol` varchar(20) NOT NULL DEFAULT ' ',
  `type` varchar(20) NOT NULL DEFAULT ' ',
  `folder` varchar(255) NOT NULL DEFAULT ' ',
  `name` varchar(255) NOT NULL DEFAULT ' ',
  `folder2` varchar(255) NOT NULL DEFAULT ' ',
  `name2` varchar(255) NOT NULL DEFAULT ' ',
  `action` varchar(20) NOT NULL DEFAULT ' ',
  `vars` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

## Changelog:##

v0.6.0
* add : OC6 friendly
* add : Apps enable/disable

v0.5.15
* fix js code warning

v0.5.14
* carddav protocol patch

v0.5.13
* caldav protocol patch

v0.5.12
* Correct file and folder name encoding
* webdav protocol patch

v0.5.11
* Correct number increments in js list

v0.5.10
* Bug fixes
* Publish the app

v0.5.0
* Add CSS styles

v0.4.0
* Add human readable logs

v0.3.0
* Add webdav support with core patch #bad :(

v0.2.0
* Add logs lifetime settings

v0.1.0
* create the app
