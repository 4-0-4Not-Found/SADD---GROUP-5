<?php
// functions.php

if (!function_exists('countNewViolators')) {
    function countNewViolators($conn) {
        $count = 0;
        
        // Count students with 5+ absences in last 7 days
        $sql_absences = "SELECT COUNT(DISTINCT student_name) as count 
                        FROM archive 
                        WHERE status = 'absent'
                        AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                        GROUP BY student_name, block
                        HAVING COUNT(*) >= 5";
        
        $result_absences = mysqli_query($conn, $sql_absences);
        if ($result_absences) {
            $count += mysqli_num_rows($result_absences);
        }
        
        // Count students with 5+ lates in last 7 days
        $sql_lates = "SELECT COUNT(DISTINCT student_name) as count 
                     FROM archive 
                     WHERE status = 'late'
                     AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                     GROUP BY student_name, block
                     HAVING COUNT(*) >= 5";
        
        $result_lates = mysqli_query($conn, $sql_lates);
        if ($result_lates) {
            $count += mysqli_num_rows($result_lates);
        }
        
        return $count;
    }
}
?>