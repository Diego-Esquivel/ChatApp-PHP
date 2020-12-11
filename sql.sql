-- Create the my_guitar_shop1 database
DROP DATABASE IF EXISTS my_simple_chat;
CREATE DATABASE my_simple_chat;
USE my_simple_chat;  -- MySQL command
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';


-- create the tables
CREATE TABLE sign_up (
	userID int NOT NULL AUTO_INCREMENT ,
	username VARCHAR(255) NOT NULL ,
	password VARCHAR(255) NOT NULL ,
	user_type VARCHAR(255) ,
	PRIMARY KEY(userID)
);

CREATE TABLE login( 
	userID int ,
	username VARCHAR(255) NOT NULL ,
	password VARCHAR(255) NOT NULL ,
	user_type VARCHAR(255) ,
	PRIMARY KEY(username) ,
	FOREIGN KEY(userID) REFERENCES sign_up(userID)
);

CREATE TABLE conversations (
	userID int NOT NULL ,
	id int NOT NULL ,
	contact_name VARCHAR(255) NOT NULL ,
	`when` int NOT NULL ,
	PRIMARY KEY(id) ,
	FOREIGN KEY(userID) REFERENCES login(userID)
);

CREATE TABLE conversation (
	message VARCHAR(255) NOT NULL ,
	`user` VARCHAR(255) NOT NULL ,
	cid int ,
	id int ,
	`when` int NOT NULL ,
	
	FOREIGN KEY(id) REFERENCES conversations(id)
);

CREATE TABLE contacts (
	userID int NOT NULL ,
	id int NOT NULL,
	username VARCHAR(255) NOT NULL ,
	`when` int NOT NULL ,
	
	FOREIGN KEY(userID) REFERENCES sign_up(userID) ,
	FOREIGN KEY(username) REFERENCES login(username)
);




INSERT INTO sign_up (userID ,username ,password ,user_type)
VALUES('0' ,'guest' ,'' ,'user');

INSERT INTO login (userID, username ,password ,user_type)
VALUES((SELECT userID FROM sign_up WHERE userID = '0') , 'guest' ,'' ,'user');