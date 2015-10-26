CREATE TABLE IF NOT EXISTS tblStudents(
    pmkNetId varchar(12) NOT NULL,
    fldFirstName varchar(255) DEFAULT NULL,
    fldLastName varchar(255) DEFAULT NULL,
    fldYear tinyint(1) unsigned DEFAULT NULL,
    fldYearEnrolled smallint(4) unsigned DEFAULT NULL,
    PRIMARY KEY(pmkNetId));

INSERT INTO tblStudents VALUES
    ('jsiebert', 'Joseph', 'Siebert', 4, 2012);