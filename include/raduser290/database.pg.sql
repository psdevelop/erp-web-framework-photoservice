-- 
-- Database : user_manager
-- --------------------------------------------------------

-- 
-- Table structure for table userProfile
-- 

DROP TABLE userProfile;

CREATE TABLE userProfile (
userId int NOT NULL default '0',
userFirstName varchar(64) NOT NULL default '',
userEmail varchar(64) NOT NULL default '',
userLastName varchar(64) NOT NULL default '',
userCompany varchar(15) NOT NULL default '',
userAddr1 varchar(64) NOT NULL default '',
userAddr2 varchar(64) NOT NULL default '',
userCity varchar(64) NOT NULL default '',
userState varchar(64) NOT NULL default '',
userCountry varchar(64) NOT NULL default '',
userTel varchar(15) NOT NULL default '',
userMobiTel varchar(15) NOT NULL default '',
userHomeTel varchar(15) NOT NULL default '',
userFax varchar(15) NOT NULL default '',
userZip varchar(10) NOT NULL default '',
userWeb varchar(128) NOT NULL default '',
userValidationKey varchar(32) NOT NULL default '',
userIP varchar(32) NOT NULL default '',
userSignUp timestamp NOT NULL default '1970-01-01 00:00:00+00',
userValidated int NOT NULL default '0',
userNewsLetter int NOT NULL default '0',
PRIMARY KEY (userId)
) ;


-- 
-- Table structure for table users
-- 
DROP TABLE users;
CREATE TABLE users (
userId SERIAL,
userStatus int NOT NULL default '0',
userName char(40) NOT NULL default '0',
userPassword char(48) NOT NULL default '0',
PRIMARY KEY (userId),
UNIQUE (userName)
) ;


-- 
-- Dumping data for table users
-- 

INSERT INTO users VALUES (1, 2, 'admin', md5('radmin'));

INSERT INTO users VALUES (2, 1, 'user', md5('radmin'));


INSERT INTO userProfile VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '915d1af3f1bdc574af6a2b3dda376d59', '127.0.0.1', '2003-11-08 11:22:45', 1, 1);

INSERT INTO userProfile VALUES (2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '605ea14eb5caa8b6a0be77eb43f38c18', '127.0.0.1', '2003-11-08 13:34:49', 1, 1);




-- 
-- Table structure for table loggedUsers
--
DROP TABLE loggedUsers;
CREATE TABLE loggedUsers (
userId int NOT NULL default '0',
sessionId char(32) NOT NULL default '',
loginTime timestamp NOT NULL default '1970-01-01 00:00:00+00',
lastAccess timestamp default NULL,
PRIMARY KEY (userId,sessionId)
) ;
CREATE INDEX loggedUsers_lastAccess_idx ON loggedUsers (lastAccess);


DROP TABLE sessions;
CREATE TABLE sessions (
session_id varchar(32) NOT NULL default '',
session_data text NOT NULL,
session_expiration timestamp NOT NULL,
PRIMARY KEY (session_id)
) ;