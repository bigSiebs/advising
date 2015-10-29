CREATE TABLE IF NOT EXISTS tblSemesterPlans(
    fnkPlanId int(11) NOT NULL,
    fldYear char(9) NOT NULL,
    fldTerm varchar(6) NOT NULL,
    fldDisplayOrder tinyint(2) DEFAULT NULL,
    PRIMARY KEY(fnkPlanId, fldYear, fldTerm));

INSERT INTO tblSemesterPlans VALUES
    (1, '2009-2010', 'Fall', 0),
    (1, '2009-2010', 'Spring', 2),
    (1, '2010-2011', 'Fall', 0),
    (1, '2010-2011', 'Spring', 2),
    (1, '2011-2012', 'Fall', 0),
    (1, '2011-2012', 'Spring', 2),
    (1, '2012-2013', 'Fall', 0),
    (1, '2012-2013', 'Spring', 2),
    (1, '2014-2015', 'Spring', 2),
    (1, '2014-2015', 'Summer', 3),
    (1, '2015-2016', 'Fall', 0),
    (1, '2015-2016', 'Spring', 2),
    (1, '2016-2017', 'Fall', 0);
