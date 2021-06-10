-- Table 1 - Messeges

DROP TABLE IF EXISTS message;

CREATE TABLE message(
srcMSISDN VARCHAR(20) NOT NULL,
destMSISDN VARCHAR(20),
receivedDate VARCHAR(100) NOT NULL,
bearer VARCHAR(5),
messageRef VARCHAR(20),
id VARCHAR(20),
switch1 INT,
switch2 INT,
switch3 INT,
switch4 INT,
fan INT,
forward INT,
reverse INT,
heater INT,
temperature DECIMAL(5,2),
keypad INT,
CONSTRAINT messege_PK PRIMARY KEY(srcMSISDN, receivedDate)
);


-- Table 2 - Users

DROP TABLE IF EXISTS users;

create table users(
User_ID INT(100) AUTO_INCREMENT NOT NULL,
Username VARCHAR(255),
Password VARCHAR(255),
Email VARCHAR(255) UNIQUE,
CONSTRAINT users_PK PRIMARY KEY(User_ID)
);

-- Table 3 - Logs

DROP TABLE IF EXISTS log;

create table log(
ID int NOT NULL AUTO_INCREMENT,
date TIMESTAMP NOT NULL,
userID INT,
msg  VARCHAR(255),
CONSTRAINT log_PK PRIMARY KEY(ID));

-- Create entries for messages

INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:44:01','SMS','20-3110-AI','20-3110-AI','1','1','0','0','1','1','0','0','30','8');
INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:45:02','SMS','20-3110-AI','20-3110-AI','1','1','0','0','0','1','0','0','30','8');
INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:46:03','SMS','20-3110-AI','20-3110-AI','1','0','1','0','1','1','0','0','34','4');
INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:48:04','SMS','20-3110-AI','20-3110-AI','1','1','1','1','1','0','1','0','38','3');
INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:51:01','SMS','20-3110-AI','20-3110-AI','1','1','0','1','0','0','1','0','33','9');
INSERT INTO message VALUES('447817814149','447817814149','18/01/2021 03:42:01','SMS','20-3110-AI','20-3110-AI','1','0','1','0','1','0','0','0','39','5');
