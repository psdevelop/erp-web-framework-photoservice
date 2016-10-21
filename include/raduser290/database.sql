#
# Database : `user_manager`
# --------------------------------------------------------

#
# Table structure for table `userProfile`
#

DROP TABLE IF EXISTS userProfile;
CREATE TABLE userProfile (
  userId int(11) NOT NULL default '0',
  userFirstName varchar(64)  NOT NULL default '',
  userEmail varchar(64)  NOT NULL default '',
  userLastName varchar(64)  NOT NULL default '',
  userCompany varchar(15)  NOT NULL default '',
  userAddr1 varchar(64)  NOT NULL default '',
  userAddr2 varchar(64)  NOT NULL default '',
  userCity varchar(64)  NOT NULL default '',
  userState varchar(64)  NOT NULL default '',
  userCountry varchar(64)  NOT NULL default '',
  userTel varchar(15)  NOT NULL default '',
  userMobiTel varchar(15)  NOT NULL default '',
  userHomeTel varchar(15)  NOT NULL default '',
  userFax varchar(15)  NOT NULL default '',
  userZip varchar(10)  NOT NULL default '',
  userWeb varchar(128)  NOT NULL default '',
  userValidationKey varchar(32)  NOT NULL default '',
  userIP varchar(32)  NOT NULL default '',
  userSignUp datetime NOT NULL default '0000-00-00 00:00:00',
  userValidated tinyint(1) NOT NULL default '0',
  userNewsLetter tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (userId)
) TYPE=MyISAM;

#
# Table structure for table `users`
#
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  userId int(11) NOT NULL auto_increment,
  userStatus tinyint(4) NOT NULL default '0',
  userName char(40) NOT NULL default '0',
  userPassword char(48) NOT NULL default '0',
  PRIMARY KEY  (userId),
  UNIQUE KEY userName (userName)
) TYPE=MyISAM;

#
# Dumping data for table `users`
#

INSERT INTO users VALUES (1, 2, 'admin', md5('radmin'));
INSERT INTO users VALUES (2, 1, 'user', md5('radmin'));

INSERT INTO userProfile VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '915d1af3f1bdc574af6a2b3dda376d59', '127.0.0.1', '2003-11-08 11:22:45', 1, 1);
INSERT INTO userProfile VALUES (2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '605ea14eb5caa8b6a0be77eb43f38c18', '127.0.0.1', '2003-11-08 13:34:49', 1, 1);



#
# Table structure for table `loggedUsers`
#
DROP TABLE IF EXISTS loggedUsers;
CREATE TABLE loggedUsers (
  userId int(11) NOT NULL default '0',
  sessionId char(32) NOT NULL default '',
  loginTime datetime NOT NULL default '0000-00-00 00:00:00',
  lastAccess datetime default NULL,
  PRIMARY KEY  (userId,sessionId),
  KEY lastAccess (lastAccess)
) TYPE=MyISAM;

DROP TABLE IF EXISTS sessions;
CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL default '',
  `session_data` text NOT NULL,
  `session_expiration` timestamp NOT NULL,
  PRIMARY KEY  (`session_id`)
) TYPE=InnoDb;
