<?php

namespace App\Models;

use App\core\Model;

class B2BInterestModel extends Model
{
    // Creates a new B2B Interest record
    public function create()
    {
        $data = [
            'username' => $_SESSION['user_name'],
            'message' => $_POST['message'],
            'recstamp' => date('d/m/Y - H:i:s'),
        ];

        // Convert the data to JSON format
        $jsonData = json_encode($data);

        try {
            $sql = "INSERT INTO b2b_interested (email, comments, agent) VALUES (?, ?, ?)";
            $values = ["ssi", $_POST['email'], $jsonData, $_SESSION['user_id']];

            return $this->executeSql($sql, $values);
        } catch (\Exception $e) {
            // Handle exceptions such as database errors
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    // Updates an existing B2B Interest record by concatenating new comments
    public function update($id)
    {
        try {
            // Fetch the existing comments
            $existingRecord = $this->selectSql("SELECT comments FROM b2b_interested WHERE id = ?", ["i", $id]);

            if (empty($existingRecord)) {
                return ['error' => 'Record not found'];
            }

            // Decode the existing comments JSON
            $existingComments = json_decode($existingRecord[0]['comments'], true);

            // Prepare the new comment data
            $newComment = [
                'username' => $_SESSION['user_name'],
                'message' => $_POST['message'],
                'recstamp' => date('d/m/Y - H:i:s'),
            ];

            // Append the new comment to the existing comments
            $existingComments[] = $newComment;

            // Convert the updated comments array back to JSON
            $updatedCommentsJson = json_encode($existingComments);

            // Update the record in the database
            $sql = "UPDATE b2b_interested SET comments = ? WHERE id = ?";
            $values = ["si", $updatedCommentsJson, $id];

            return $this->executeSql($sql, $values);
        } catch (\Exception $e) {
            // Handle exceptions such as database errors
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }
}
