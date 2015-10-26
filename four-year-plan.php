<?php
include "top.php";

print "<article>";

$query = "SELECT fldYear, fldTerm, CONCAT(fldDepartment, ' ', fldCourseNumber) AS cctCourses";
$query .= " FROM tblSemesterPlans SP";
$query .= " INNER JOIN tblSemesterPlanCourses SPC ON SP.fnkPlanId = SPC.fnkPlanId AND";
$query .= " SP.fldYear = SPC.fnkYear AND";
$query .= " SP.fldTerm = SPC.fnkTerm";
$query .= " INNER JOIN tblCourses C ON SPC.fnkCourseId = C.pmkCourseId";
$query .= " INNER JOIN tblFourYearPlans FYP ON SP.fnkPlanId = FYP.pmkPlanId";
$query .= " WHERE FYP.fnkStudentNetId = ?";
$query .= " ORDER BY fldYear, SP.fldDisplayOrder, SPC.fldDisplayOrder";

$data = array('jsiebert');

$info = $thisDatabaseReader->select($query, $data, 1, 3, 2, 0, false, false);
        
        print "<h2>Student: " . '[Student Name]' . "</h2>";
        print "<h3>Advisor: " . '[Advisor Name]' . "</h3>";
        
        if ($debug) {
            print "<p>DATA: <pre>";
            print_r($info);
            print "</pre></p>";
        }
        
        // Start printing table
        print '<table>';
        print '<tr>';
        
        // Get headings from first subarray (removes indexes with filter function)
        $headers = array_keys($info[0]);
        $fields = array_filter($headers, 'is_string'); // Picks up only str values
        // For loop to print headings
        foreach ($fields as $head) {
            print '<th>' . $head . '</th>';
        }
        
        print "</tr>";
        
        // For loop to print records
        foreach ($info as $record) {
            print '<tr>';
            // Uses field names (AKA headers) as keys to pick from arrays
            foreach ($fields as $field) {
                print '<td>' . htmlentities($record[$field]) . '</td>';
            }
            print '</tr>';
        }
        
        // Close table
        print '</table>';

print "</article>";

include "footer.php";
?>
