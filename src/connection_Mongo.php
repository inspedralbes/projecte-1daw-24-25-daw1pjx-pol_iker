
<?php
use MongoDB\Driver\ServerApi;

$uri = 'mongodb+srv://projecte_Iker_Ricardo_Pol:Pol-Ricardo-Iker@projectemongo.5pcntao.mongodb.net/?retryWrites=true&w=majority&appName=ProjecteMongo';

// Set the version of the Stable API on the client
$apiVersion = new ServerApi(ServerApi::V1);

// Create a new client and connect to the server
$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

try {
    // Send a ping to confirm a successful connection
    $client->selectDatabase('MongoDB_Projecte')->command(['ping' => 1]);
    echo "Pinged your deployment. You successfully connected to MongoDB!\n";
    $collection = $client->MongoDB_Projecte->logs;
    
} catch (Exception $e) {
    printf($e->getMessage());
}

