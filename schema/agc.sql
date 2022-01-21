USE nirsal_agc_app
GO

CREATE TABLE agc_application (
	application_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	user_id INT,
	application_status SMALLINT DEFAULT 0,
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE agc_application_stages (
	stage_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	stage_name VARCHAR(50) NOT NULL UNIQUE,
	stage_description TEXT
);

CREATE TABLE agc_application_stage (
	application_stage_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	stage_name VARCHAR(50),
	stage_status SMALLINT DEFAULT 0,
	stage_note TEXT,
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	last_modified TIMESTAMP,

	UNIQUE (application_id, stage_name),
	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (stage_name) REFERENCES agc_application_stages (stage_name) ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE agc_application_kyl_data (
	kyl_data_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	leader_bvn VARCHAR(13) NOT NULL,
	kyl_leader_type VARCHAR(50) NOT NULL,
	residential_state VARCHAR(50),
	residential_lga VARCHAR(50),
	contact_address VARCHAR(50),
	academic_qualification VARCHAR(50),
	work_experience VARCHAR(50),
	leader_questionnaire TEXT DEFAULT NULL,

	UNIQUE (application_id, leader_bvn),
	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE agc_application_kyc_data (
	kyc_data_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	agc_name VARCHAR(50) NOT NULL UNIQUE,
	commodity_category VARCHAR(50),
	commodity_type VARCHAR(50),
	total_land_size_hectares INT,
	total_farmers INT,
	agc_state VARCHAR(50),
	agc_lga VARCHAR(50),
	agc_city VARCHAR(50),
	is_bvn_validated SMALLINT DEFAULT 0,
	is_crc_complete SMALLINT DEFAULT 0,

	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE agc_application_kyc_farmers (
	agc_farmer_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	farmer_bvn VARCHAR(13) NOT NULL,
	crc_status SMALLINT DEFAULT 0,
	crc_status_note TEXT,
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	last_modified TIMESTAMP,

	UNIQUE (application_id, farmer_bvn),
	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE agc_application_kyf_data (
	kyf_data_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	parcelization_url VARCHAR(500),
	is_allocation_collected SMALLINT DEFAULT 0,
	is_parcelization_complete SMALLINT DEFAULT 0,
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	last_modified TIMESTAMP,

	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE agc_application_kyf_allocations (
	agc_allocation_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	agc_farmer_id INT,
	farmer_allocation_hectares INT,

	FOREIGN KEY (agc_farmer_id) REFERENCES agc_application_kyc_farmers (agc_farmer_id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE agc_application_kyc_farmers_data(
	farmers_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	application_id INT,
	farmers_name VARCHAR(100),
	farmer_bvn VARCHAR(20),
	farmer_phone VARCHAR(20),
	crc_status VARCHAR(10) DEFAULT NULL,

	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE field_verification(
	field_verification_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	agcName VARCHAR(50),
	application_id INT,
	field_verification_officer VARCHAR(50),
	verification_date DATE NOT NULL,
	state VARCHAR(20),
	lga VARCHAR(50),
	farmlandLocation VARCHAR(50),
	latitude DECIMAL(7,6),
	longitude DECIMAL(7,6),
	proposedCrop VARCHAR(50),
	proposedSeason VARCHAR(50),
	recommendedPlantingDate DATE NOT NULL,
	plantingDeadline DATE NOT NULL,
	verifiedLandSize VARCHAR (20),
	isLandCountigous VARCHAR(3),
	areBoundriesDemacated VARCHAR(3),
	isLandPercelized VARCHAR(3),
	describeFieldType VARCHAR(10),
	describeLandTopography VARCHAR(10),
	isLandTractorAble VARCHAR(3),
	visualAssesment VARCHAR(50),
	availableWaterResources VARCHAR(50),
	landmarks VARCHAR(50),
	proximityToNonAgricLand VARCHAR(50),
	landFeatures VARCHAR(50),
	siteAssessibilty VARCHAR(50),
	agriculturalPrograms VARCHAR(50),
	existingCooperatives VARCHAR(50),
	relevantInformation VARCHAR(100),
	challenges VARCHAR(100),
	siteVisitation VARCHAR(100),
	pmroVerdict VARCHAR(50),
	field_verification_status SMALLINT DEFAULT 0,


	FOREIGN KEY (application_id) REFERENCES agc_application (application_id) ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE head_of_pmros(
	pmro_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	full_name VARCHAR(100),
	designation VARCHAR(30),
	state VARCHAR(50),
	email VARCHAR(50),
	phone_number VARCHAR(50)

)

CREATE TABE cost_incured(
		cost_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		application_id INT,
		cost INT,
		cost_status DEFAULT unpaid
)
