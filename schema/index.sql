CREATE DATABASE nirsal_agc_app;
GO;

CREATE TABLE users (
	user_id INT PRIMARY KEY AUTO_INCREMENT,
	user_bvn VARCHAR(13) UNIQUE,
	user_email VARCHAR(50) NOT NULL,
	user_password VARCHAR(500) NOT NULL,
	first_name VARCHAR(50),
	last_name VARCHAR(50)
)
GO

CREATE TABLE bvn_retrieved_bvns (
	bvn_id INT PRIMARY KEY AUTO_INCREMENT,
	bvn VARCHAR(13) NOT NULL UNIQUE,
	date_last_retrieved DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	retrieved_by INT,
	CONSTRAINT fk_retrievedBvns_retrievedBy_users_userId
		FOREIGN KEY (retrieved_by) REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE SET NULL
)
GO

CREATE TABLE bvn_retrieved_bvn_data (
	bvn_data_id INT PRIMARY KEY AUTO_INCREMENT,
	bvn VARCHAR(13) NOT NULL,
	data_field VARCHAR(50) NOT NULL,
	data_value VARCHAR(MAX),
	CONSTRAINT fk_retrievedBvnData_bvn_retrievedBvns_bvn
		FOREIGN KEY (bvn) REFERENCES bvn_retrieved_bvns (bvn) ON UPDATE CASCADE ON DELETE CASCADE
)
GO

CREATE TABLE logs_bvn_search (
	search_id INT PRIMARY KEY AUTO_INCREMENT,
	searched_bvn VARCHAR(13) NOT NULL,
	user_id INT,
	search_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_bvnSearch_userId_users_userId
		FOREIGN KEY (user_id) REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE SET NULL
)
GO