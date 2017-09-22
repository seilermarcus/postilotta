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
    ReturnLink VARCHAR(255),
    Expire DATETIME
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
    PaidUntil DATE,
    MsgLife INT DEFAULT 120,
    IdVerified BOOLEAN DEFAULT NULL,
    Info VARCHAR(255)

);

CREATE TABLE Paranoia (
    PLink VARCHAR (255) PRIMARY KEY,
    Passphrase VARCHAR (255),
    Watchword VARCHAR (255),
    Expire TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE EVENT MessageExpire
    ON SCHEDULE EVERY 1 HOUR
    COMMENT 'Deletes expired messages.'
    DO
      DELETE FROM Message WHERE Expire <= UTC_TIMESTAMP();

CREATE EVENT ParanoiaExpire
    ON SCHEDULE EVERY 1 HOUR
    COMMENT 'Deletes expired ExtraSecure Sessions.'
    DO
      DELETE FROM Paranoia WHERE Expire <= UTC_TIMESTAMP();

CREATE VIEW v_Msg AS SELECT MsgID, Recipient, Date, State, Expire FROM Message;
CREATE VIEW v_Inb AS SELECT Address, EMail, Visible, Type, Payment, Price, PaidUntil, MsgLife, IdVerified, Info FROM Inbox;

SET GLOBAL event_scheduler = ON;
SET GLOBAL time_zone = '+00:00';
