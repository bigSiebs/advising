CREATE TABLE IF NOT EXISTS tblAdvisors(
    pmkNetId varchar(12) NOT NULL,
    fldFirstName varchar(255) DEFAULT NULL,
    fldLastName varchar(255) DEFAULT NULL,
    PRIMARY KEY(pmkNetId));

INSERT INTO tblAdvisors VALUES
    ('rsnapp', 'Robert Raymond', 'Snapp');