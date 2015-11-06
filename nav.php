<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item 
        if ($path_parts['filename'] == "tables") {
            print '<li class="activePage">Display Tables</li>';
        } else {
            print '<li><a href="tables.php">Display Tables</a></li>';
        }
        
        if ($path_parts['filename'] == "four-year-plan") {
            print '<li class="activePage">My Four Year Plan</li>';
        } else {
            print '<li><a href="four-year-plan.php">My Four Year Plan</a></li>';
        }
        
        if ($path_parts['filename'] == "form") {
            print '<li class="activePage">Add Your Plan</li>';
        } else {
            print '<li><a href="form.php">Add Your Plan</a></li>';
        }
        ?>
    </ol>
</nav>
<!-- #################### Ends Main Navigation    ########################## -->

