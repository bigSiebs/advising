<?php

include "top.php";

print "<article>";

$query = "SELECT pmkPlanId, fldDateCreated, fldCatalogYear, fnkStudentNetId, fnkAdvisorNetId, fldYear, fldTerm, CONCAT(fldDepartment, ' ', fldCourseNumber) AS cctCourses";
$query .= " FROM tblFourYearPlans FYP";
$query .= " INNER JOIN tblSemesterPlans SP ON FYP.pmkPlanId = SP.fnkPlanId";
$query .= " INNER JOIN tblSemesterPlanCourses SPC ON SP.fnkPlanId = SPC.fnkPlanId AND";
$query .= " SP.fldYear = SPC.fnkYear AND";
$query .= " SP.fldTerm = SPC.fnkTerm";
$query .= " INNER JOIN tblCourses C ON SPC.fnkCourseId = C.pmkCourseId";
$query .= " WHERE FYP.fnkStudentNetId = ?";
$query .= " ORDER BY fldYear, SP.fldDisplayOrder, SPC.fldDisplayOrder";

$data = array('jsiebert');

$info = $thisDatabaseReader->select($query, $data, 1, 3, 2, 0, false, false);

print "<h2>Student: " . $info[0]['fnkStudentNetId'] . "</h2>";
print "<h3>Advisor: " . $info[0]['fnkAdvisorNetId'] . "</h3>";

if ($debug) {
    print "<p>DATA: <pre>";
    print_r($info);
    print "</pre></p>";
}

// Start printing table
print '<table>';
//print '<tr>Four-Year Plan';
// Get headings from first subarray (removes indexes with filter function)
$headers = array_keys($info[0]);
$fields = array_filter($headers, 'is_string'); // Picks up only str values
// For loop to print headings
//foreach ($fields as $head) {
//    print '<th>' . $head . '</th>';
//}
//print "</tr>";

$currentTerm = "";
$currentYear = "First";

// For loop to print records
foreach ($info as $record) {
    $nextTerm = $record['fldTerm'];
    $nextYear = $record['fldYear'];

    if ($currentYear != $nextYear) {
        if ($currentYear != "First") {
            print '</ol></td></tr>';
        }
        print '<tr>';
        print '<td>' . $nextYear . '</td>';
        print '<td><h6>' . $nextTerm . '</h6><ol>';
        
        $currentYear = $nextYear;
        $currentTerm = $nextTerm;
    }

    else if ($currentTerm != $nextTerm) {
        print '</ol></td>';
        print '<td><h6>' . $nextTerm . '</h6><ol>';
        $currentTerm = $nextTerm;
    }
    print '<li>' . htmlentities($record['cctCourses']) . '</li>';
}

// Close table
print '</ol></td></tr></table>';

print "</article>";

include "footer.php";
?>
