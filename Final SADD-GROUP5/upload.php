<?php
include_once("include/connect.php");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["block_id"]) && isset($_POST["user_id"])) {
    $block_id = $_POST["block_id"];
    $user_id = $_POST["user_id"];

    if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES["fileInput"]["tmp_name"];

        $reader = IOFactory::createReaderForFile($fileTmpPath);
if ($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Xlsx) {
    $reader->setReadDataOnly(true);
}

$spreadsheet = $reader->load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();

        $success = true; // Flag to track if all inserts are successful
        $rowIndex = 1; // Initialize row index

        foreach ($sheet->getRowIterator() as $row) {
            // Skip first two rows and last two rows
            if ($rowIndex > 2 && $rowIndex < $sheet->getHighestRow() - 1) {
                $bu_no = $sheet->getCell('A' . $rowIndex)->getValue();
                $name = $sheet->getCell('B' . $rowIndex)->getValue();
                $reg_id = $sheet->getCell('C' . $rowIndex)->getValue();
                $status = $sheet->getCell('D' . $rowIndex)->getValue();
                $birthdate = $sheet->getCell('E' . $rowIndex)->getValue();
                $gender = $sheet->getCell('F' . $rowIndex)->getValue();
                $year_level = $sheet->getCell('G' . $rowIndex)->getValue();
                $contact_number = $sheet->getCell('H' . $rowIndex)->getValue();
                $email_add1 = $sheet->getCell('I' . $rowIndex)->getValue();
                $email_add2 = $sheet->getCell('J' . $rowIndex)->getValue();
                
                $sql = "INSERT INTO `students` (`bu_no`, `name`, `reg_id`, `status`, `birthdate`, `gender`, `year_level`, `contact_number`, `email_add1`, `email_add2`, `block`, `user_id`) 
                        VALUES ('$bu_no', '$name', '$reg_id', '$status', '$birthdate', '$gender', '$year_level', '$contact_number', '$email_add1', '$email_add2', '$block_id', '$user_id')";
                
                if ($conn->query($sql) !== TRUE) {
                    $success = false; // Set success flag to false if any insert fails
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    break; // Exit loop if an error occurs
                } else {
                    // Update status to "active"
                    $updateSql = "UPDATE `students` SET `status` = 'active' WHERE `bu_no` = '$bu_no'";
                    if ($conn->query($updateSql) !== TRUE) {
                        echo "Error updating status: " . $conn->error;
                        break; // Exit loop if an error occurs during status update
                    }
                }
            }
            $rowIndex++; // Increment row index
        }

        if ($success) {
            echo "Data inserted successfully!";
            header("Location: class.php");
            exit();
        }

        $conn->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>