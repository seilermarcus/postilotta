DROP DATABASE IF EXISTS `postilotta_msgng`;
CREATE DATABASE postilotta_msgng ;
USE postilotta_msgng;
CREATE TABLE Message (
    MsgID INT PRIMARY KEY,
    Recipient VARCHAR(255),
    Date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  State VARCHAR(255) DEFAULT "NEW",
    Content MEDIUMBLOB,
    ReturnPubKey BLOB,
    ReturnLink VARCHAR(255)
);
CREATE TABLE Inbox (
    BoxID int PRIMARY KEY,
    Address varchar (255) UNIQUE,
    PubKey varchar (255),
    Password varchar (255),
    Email varchar (255)
);
CREATE TABLE Paranoia (
    PLink varchar (255) PRIMARY KEY,
    Passphrase varchar (255),
    Watchword varchar (255),
    Time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
