CREATE TABLE IF NOT EXISTS tblFourYearPlans(
    pmkPlanId int(11) NOT NULL AUTO_INCREMENT,
    fnkStudentNetId varchar(12) NOT NULL,
    fnkAdvisorNetId varchar(12) NOT NULL,
    fldDateCreated TIMESTAMP DEFAULT NOW(),
    fldCatalogYear smallint(4) DEFAULT NULL,
    fldMajor varchar(255) DEFAULT NULL,
    fldMinor varchar(255) DEFAULT NULL,
    PRIMARY KEY(pmkPlanId));

INSERT INTO tblFourYearPlans VALUES
    (1, 'jsiebert', 'rsnapp', NOW(), 2016, 'Computer Science', 'English');