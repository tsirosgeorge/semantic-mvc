<?php

namespace App\Controllers\Api;

use App\core\Controller;
use App\core\Auth;
use App\Models\B2BInterestModel;

class B2BInterestController extends Controller
{
    public function create()
    {
        // Check if the user is authenticated
        Auth::check();

        // Instantiate the model and call the create method
        $data = (new B2BInterestModel())->create();

        // Send a JSON response with the result
        $this->jsonResponse(['success' => true, 'message' => 'Create', 'data' => $data]);
    }

    public function update($id)
    {
        // Check if the user is authenticated
        Auth::check();

        // Pass the ID to the update method in the model
        $data = (new B2BInterestModel())->update($id);

        // Send a JSON response with the result
        $this->jsonResponse(['success' => true, 'message' => 'Update', 'data' => $data]);
    }
}
