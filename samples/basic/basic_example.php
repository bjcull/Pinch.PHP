<?php
// Basic example of fetching an access token and some data

include 'pinch.php';

$access_token = get_access_token($auth_client, $merchant_id, $secret_key);

$payers = pinch_http_get('payers', $client, $access_token);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pinch PHP Examples</title>
</head>
<body>
    <h1>Pinch PHP Examples</h1>
    <p>A quick set of examples for using Pinch's API</p>

<h3>Access Token</h3>
<p>
    <?php
        echo $access_token;        
    ?>
</p>

<h3>Payers</h3>
<pre>
<?php
echo json_encode($payers, JSON_PRETTY_PRINT);
?>
</pre>

</body>
</html>
