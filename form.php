<?php

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$studentID = "";
$advisorID = "";
$catalogYear = "2015-2016";
$major = "Computer Science";
$minor = "English";

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1d: Form error flags: Initalize ERROR flags, one for each form element
// we validate, in the order they appear in SECTION 1c

$studentIDError = false;
$advisorIDError = false;

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1e: Misc. variables
// Array to hold error messages
$errorMsg = array();

// Array to hold form values to be inserted into mySQL database
$dataRecord = array();

$mailed = false; // Not mailed yet
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 2: Process for when the form is submitted

if (isset($_POST['btnSubmit'])) {
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2a: Security

    if (!securityCheck($path_parts, $yourURL, true)) {
        $msg = '<p>Sorry, you cannot access this page. ';
        $msg.= 'Security breach detected and reported.';
        die($msg);
    }
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2b: Sanitize data
    
    // Remove any potential JS or HTML code from users input on the form.
    // Follow same order as declared in SECTION 1c.
    
    $studentID = htmlentities($_POST['txtStudentID'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $studentID;
    
    $advisorID = htmlentities($_POST['txtAdvisorID'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $advisorID;
    
    $catalogYear = htmlentities($_POST['lstYear'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $catalogYear;
    
    $major = htmlentities($_POST['lstMajor'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $major;
    
    $minor = htmlentities($_POST['lstMinor'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $minor;
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2c: Validation: Check each value for possible errors or empty.
    
    if ($studentID == "") {
        $errorMsg[] = "Please enter the student's NetID.";
        $studentIDError = true;
    } elseif (!verifyAlphaNum($studentID)) {
        $errorMsg[] = "Student NetID appears to include invalid charaters.";
        $studentIDError = true;
    }
    
    if ($advisorID == "") {
        $errorMsg[] = "Please enter the advisor's NetID.";
        $advisorIDError = true;
    } elseif (!verifyAlphaNum($advisorID)) {
        $errorMsg[] = "Advisor NetID appears to include invalid charaters.";
        $advisorIDError = true;
    }
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2d: Process form - passed validation (errorMsg is empty)
    
    if (!$errorMsg) {
        if ($debug) {
            print "<p>Form is valid.</p>";
        }
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 2e: Save data: Insert data into database   
        
        // Add insert method
        
        $query = "INSERT INTO tblFourYearPlans(fnkStudentNetId, fnkAdvisorNetId, fldCatalogYear, fldMajor, fldMinor) VALUES (?, ?, ?, ?, ?)";
        $data = array($studentID, $advisorID, $catalogYear, $major, $minor);
        print "<p>SQL: " . $query;
        $plan = $thisDatabaseWriter->insert($query, $data, 0, 0, 0, 0, false, false);
        $planID = $thisDatabaseWriter->lastInsert();
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 2f: Create message
    
        $message = "<h2>Your plan has been saved.</h2>";
        $message.= "<p>A copy of your plan appears below.</p>";
        
        foreach ($_POST as $key => $value) {
            $message.= "<p>";
            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
            
            foreach ($camelCase as $one) {
                $message.= $one . " ";
            }
            $message.= ": " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 2g: Mail to user
    
        $email = $studentID . "@uvm.edu";
        
        $to = $email; // the person who filled out form
        $cc = ""; // would add advisor here
        $bcc = "";
        $from = "UVM Advising <jsiebert@uvm.edu>";
        
        // subject of mail should match form
        $todaysDate = strftime("%x");
        $subject = "Advising Form, submitted " . $todaysDate;
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // ends form is valid
} // ends if form was submitted

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 3: Display form
// 
?>

<article id="main">
    <h2>Form</h2>
    
    <?php
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 3a
    
    // If its the first time coming to form or there are errors, display form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing marked with 'end body submit'
        print "<h2>Your request has ";
        
        if (!$mailed) {
            print 'not ';
        }
        
        print "been processed.</h2>";
        
        if ($mailed) {
            print "<p>A copy of this message has been sent to: " . $email . ".</p>";
            print "<p>Mail message:</p>";
            print $message;
        }
        
    } else {
        
    
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 3b: Error messages: Display any error message before we print form
    
        if ($errorMsg) {
            print '<div class="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "\t<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print "</div>";
        }
    
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 3c: HTML form: Display HTML form
        // Action is to this same page. $phpSelf is defined in top.php
        /* Note lines like: value="<?php print $email; ?> 
         * These make the form sticky by displaying the default value or
         * the value that was typed in previously.
         * Also note lines like <?php if ($emailERROR) print 'class="mistake"'; ?> 
         * These allow us to use CSS to identify errors with style. */
    ?>
    
    <form action="<?php print $phpSelf; ?>"
          method="post"
          id="frmCreatePlan">
        
        <fieldset class="wrapper">
            <legend></legend>
            <p>Please provide the following information about yourself or your student.</p>
            
            <fieldset class="student-info">
                <legend>Student Information</legend>
                <label for="txtStudentID" class="required">Student Net ID
                    <input type="text" id="txtStudentID" name="txtStudentID"
                           value="<?php print $studentID; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter the student's NetID"
                           <?php if ($studentIDError) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           autofocus>
                </label>
                
                <label for="txtAdvisorID" class="required">Advisor Net ID
                    <input type="text" id="txtAdvisorID" name="txtAdvisorID"
                           value="<?php print $advisorID; ?>"
                           tabindex="110" maxlength="45" placeholder="Enter the advisor's NetID"
                           <?php if ($advisorIDError) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           autofocus>
                </label>
                
                <fieldset class="listbox1">
                        <label for="lstYear">Catalog Year</label>
                        <select id="lstYear" name="lstYear"
                                tabIndex="200">
                        <?php
                        // Array for listbox options
                        $list1Choices = array("2012-2013", "2013-2014", "2014-2015", "2015-2016");
                        
                        foreach ($list1Choices as $option) {
                            print "\n\t\t\t" . "<option ";
                            if ($catalogYear == $option) {
                                print 'selected ';
                            }
                            print 'value="' . $option . '">' . $option . "</option>";
                            print "\n";
                        }
                        ?>
                    </select>
                </fieldset> <!-- end listbox1 -->
                
                <fieldset class="listbox2">
                        <label for="lstMajor">Major</label>
                        <select id="lstMajor" name="lstMajor"
                                tabIndex="210">
                        <?php
                        // Array for listbox options
                        $list2Choices = array("Anthropology", "Computer Science", "Music", "Physics");
                        
                        foreach ($list2Choices as $option) {
                            print "\n\t\t\t" . "<option ";
                            if ($major == $option) {
                                print 'selected ';
                            }
                            print 'value="' . $option . '">' . $option . "</option>";
                            print "\n";
                        }
                        ?>
                    </select>
                </fieldset> <!-- end listbox2 -->
                
                <fieldset class="listbox3">
                        <label for="lstMinor">Minor</label>
                        <select id="lstMinor" name="lstMinor"
                                tabIndex="230">
                        <?php
                        // Array for listbox options
                        $list3Choices = array("Anthropology", "Computer Science", "English", "Music", "Physics");
                        
                        foreach ($list3Choices as $option) {
                            print "\n\t\t\t" . "<option ";
                            if ($minor == $option) {
                                print 'selected ';
                            }
                            print 'value="' . $option . '">' . $option . "</option>";
                            print "\n";
                        }
                        ?>
                    </select>
                </fieldset> <!-- end listbox3 -->
                
            </fieldset> <!-- end student-info -->
            
            <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->
            
        </fieldset> <!-- end wrapper! -->
    </form> <!-- end form! -->
    
    <?php
    } // end body submit
    ?>
    
</article>

<?php
include 'footer.php';
?>