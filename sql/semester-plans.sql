CREATE TABLE IF NOT EXISTS tblSemesterPlans(
    fnkPlanId int(11) NOT NULL,
    fldYear char(9) NOT NULL,
    fldTerm varchar(6) NOT NULL,
    fldDisplayOrder tinyint(2) DEFAULT NULL,
    PRIMARY KEY(fnkPlanId, fldYear, fldTerm));

INSERT INTO tblSemesterPlans VALUES
    (1, '2012-2013', 'Fall', 0),
    (1, '2012-2013', 'Spring', 1),
    (1, '2013-2014', 'Fall', 0),
    (1, '2013-2014', 'Spring', 1),
    (1, '2014-2015', 'Fall', 0),
    (1, '2014-2015', 'Spring', 1),
    (1, '2015-2016', 'Fall', 0),
    (1, '2015-2016', 'Spring', 1);
