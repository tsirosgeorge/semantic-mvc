<?php

namespace App\Models\Reseller;

use App\core\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class FileModel extends Model
{
    public function uploadContract($id, $files)
    {


        // Get customer AFM
        $getAfm = $this->SelectSql("SELECT afm FROM customers WHERE id = ?", array('i', $id));
        if (!$getAfm || empty($getAfm[0]['afm'])) {
            return ['success' => false, 'message' => 'Invalid customer ID', 'status_code' => 400];
        }

        // File Validation
        if (!isset($files['file']) || $files['file']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error uploading file', 'status_code' => 400];
        }

        $allowedExtensions = ['pdf', 'jpg', 'png']; // Allow only specific file types
        $file_name = $files['file']['name'];
        $file_tmp = $files['file']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check for allowed file types
        if (!in_array($file_extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'Invalid file type', 'status_code' => 400];
        }

        // Generate a safe file path (local file system path)
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/';
        $filePath = $uploadDir . 'ESKAP-' . $getAfm[0]["afm"] . '.' . $file_extension;

        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $filePath)) {
            // Create the URL to access the file
            $fileURL = '/assets/uploads/' . 'ESKAP-' . $getAfm[0]["afm"] . '.' . $file_extension;

            // Update the file URL in the database
            $result = $this->executeSql("UPDATE customers SET fileurl = ? WHERE id = ?", array("si", $fileURL, $id));

            if (!$result) {
                return ['success' => false, 'message' => 'Failed to update file URL in database', 'status_code' => 500];
            }

            return ['success' => true, 'message' => 'File uploaded successfully', 'status_code' => 200];
        } else {
            return ['success' => false, 'message' => 'Failed to move uploaded file', 'status_code' => 500];
        }
    }

    public function savePDF($data)
    {
        $pdfData = $data["pdf"];
        $filename = $data["filename"];
        $email = $data["email"];
        $pdfData = substr($pdfData, strpos($pdfData, ',') + 1);

        // Decode the data URI
        $pdfData = base64_decode($pdfData);


        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            // Server settings
            $mail->SMTPDebug = 0; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'jvrzyz@gmail.com'; // SMTP username
            $mail->Password = 'jauu pdqi irjd amxp'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->Sender = 'donotreply@kappadevelopment.gr';
            $mail->setFrom('donotreply@kappadevelopment.gr', 'Mailer');
            $mail->addAddress($email); // Add a recipient

            // Attachments
            $mail->addStringAttachment($pdfData, $filename); // Add attachments

            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $filename;
            $mail->Body    = 'Καλησπέρα σας,<br>Σας προωθούμε την σύμβαση παρόχου ηλεκτρονικής τιμολόγησης (ESKAP), 
            <br>την οποία θα θέλαμε να υπογράψετε μέσω του link: <a href="https://www.gov.gr/ipiresies/polites-kai-kathemerinoteta/psephiaka-eggrapha-gov-gr/psephiake-bebaiose-eggraphou">https://www.gov.gr/ipiresies/polites-kai-kathemerinoteta/psephiaka-eggrapha-gov-gr/psephiake-bebaiose-eggraphou</a><br>(Ψηφιακή βεβαίωση εγγράφου)';

            $mail->send();
            return ['success' => true, 'message' => 'Email sent successfully', 'status_code' => 200];
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
