<?php

include "top.php";

print "<article>";

$query = "SELECT pmkPlanId, S.fldFirstName AS fldStudentFirstName, S.fldLastName AS fldStudentLastName, fnkStudentNetId, A.fldFirstName AS fldAdvisorFirstName, A.fldLastName AS fldAdvisorLastName, fnkAdvisorNetId, fldDateCreated, fldCatalogYear, SP.fldYear, fldTerm, fldDepartment, fldCourseNumber, fldCredits";
$query .= " FROM tblFourYearPlans FYP";
$query .= " INNER JOIN tblSemesterPlans SP ON FYP.pmkPlanId = SP.fnkPlanId";
$query .= " INNER JOIN tblSemesterPlanCourses SPC ON SP.fnkPlanId = SPC.fnkPlanId AND";
$query .= " SP.fldYear = SPC.fnkYear AND";
$query .= " SP.fldTerm = SPC.fnkTerm";
$query .= " INNER JOIN tblCourses C ON SPC.fnkCourseId = C.pmkCourseId";
$query .= " INNER JOIN tblAdvisors A ON FYP.fnkAdvisorNetId = A.pmkNetId";
$query .= " INNER JOIN tblStudents S ON FYP.fnkStudentNetId = S.pmkNetId";
$query .= " WHERE FYP.pmkPlanId = ?";
$query .= " ORDER BY SP.fldYear, SP.fldDisplayOrder, SPC.fldDisplayOrder";

$data = array(1);

$info = $thisDatabaseReader->select($query, $data, 1, 3, 0, 0, false, false);

print "<h2>Student: ";
print $info[0]['fldStudentFirstName'] . ' ' . $info[0]['fldStudentLastName'];
print "</h2>";
print "<h3>Advisor: ";
print $info[0]['fldAdvisorFirstName'] . ' ' . $info[0]['fldAdvisorLastName'];
print "</h3>";

if ($debug) {
    print "<p>DATA: <pre>";
    print_r($info);
    print "</pre></p>";
}

$currentTerm = "";
$currentYear = "First";
$termCredits = 0;
$totalCredits = 0;

// For loop to print records
foreach ($info as $record) {
    $nextTerm = $record['fldTerm'];
    $nextYear = $record['fldYear'];

    if ($currentYear != $nextYear) {
        if ($currentYear != "First") {
            print '</ol>';
            print '<p>Total credits: ' . $termCredits . '</p>';
            $termCredits = 0;
            print '</div><div class="clear_left">';
        } else {
            print '<div>';
        }
        print $nextYear;
        print '</div>';
        print '<div><h3>' . $nextTerm . '</h3><ol>';

        $currentYear = $nextYear;
        $currentTerm = $nextTerm;
    } else if ($currentTerm != $nextTerm) {
        print '</ol>';
        print '<p>Total credits: ' . $termCredits . '</p>';
        $termCredits = 0;
        print '</div><div>';
        print '<h3>' . $nextTerm . '</h3><ol>';
        $currentTerm = $nextTerm;
    }
    print '<li>' . $record['fldDepartment'] . ' ' . htmlentities($record['fldCourseNumber']) . '</li>';
    $termCredits += $record['fldCredits'];
    $totalCredits += $record['fldCredits'];
}

// Close table
print '<p>Total credits: ' . $termCredits . '</p>';
print '</ol></div>';

print '<section>';
print '<p>Total credits: ' . $totalCredits . '</p>';
print '</section>';

print "</article>";

include "footer.php";
?>
