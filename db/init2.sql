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
    BoxID INT PRIMARY KEY,
    Address VARCHAR (255) UNIQUE,
    PubKey VARCHAR (255),
    Password VARCHAR (255),
    Email VARCHAR (255),
    Visible BOOLEAN DEFAULT NULL,
    Type VARCHAR (255),
    Payment VARCHAR (255),
    Price INT,
    PaidUntil DATE
);
CREATE TABLE Paranoia (
    PLink VARCHAR (255) PRIMARY KEY,
    Passphrase VARCHAR (255),
    Watchword VARCHAR (255),
    Time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
