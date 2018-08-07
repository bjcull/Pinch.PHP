<?php
// Example of capturing a realtime credit card transaction

include 'pinch.php';

if (isset($_POST['CreditCardToken']))
{
    $access_token = get_access_token($auth_client, $merchant_id, $secret_key);

    $new_payer_data = [
        'fullName' => $_POST['PayerName'],
        'email' => $_POST['PayerEmail'],
        'amount' => $_POST['Amount'],
        'description' => $_POST['Description'],
        'creditCardToken' => $_POST['CreditCardToken']
    ];
    
    $new_payer_result = pinch_http_post('payments/realtime', $client, $access_token, $new_payer_data);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pinch PHP Examples</title>
</head>
<body>
    <h1>Pinch Credit Card Capture Example</h1>
    <p>A basic exqample for capturing credit cards in PHP</p>

<?php if (isset($_POST['CreditCardToken'])) { ?>
    <h3>Access Token</h3>
    <p>
        <?php
            echo $access_token;        
        ?>
    </p>

    <h3>Json Request</h3>
    <pre>
    <?php echo json_encode($new_payer_data, JSON_PRETTY_PRINT); ?>
    </pre>

    <h3>Json Response</h3>
    <pre>
    <?php echo json_encode($new_payer_result, JSON_PRETTY_PRINT); ?>
    </pre>

<?php } ?>

<h2>Credit Card Form</h2>

<form id="cc-form" action="credit_card.php" method="POST">
    <input id="CreditCardToken" name="CreditCardToken" type="hidden"/>
    <div class="form-horizontal">
        <div class="form-group">
            <label for="PayerName" class="col-sm-2 control-label">Payer Name</label>
            <div class="col-sm-10">
                <input id="PayerName" name="PayerName" class="form-control"/>                        
            </div>
        </div>
        <div class="form-group">
            <label for="PayerEmail" class="col-sm-2 control-label">Payer Email</label>
            <div class="col-sm-10">
                <input id="PayerEmail" name="PayerEmail" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="Amount" class="col-sm-2 control-label">Amount</label>
            <div class="col-sm-10">
                <input id="Amount" name="Amount" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="Description" class="col-sm-2 control-label">Payment Description</label>
            <div class="col-sm-10">
                <input id="Description" name="Description" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Credit Card</label>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input id="cardNumber" class="form-control" placeholder="Card Number"/>
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <input id="expiryMonth" class="form-control" placeholder="Expiry Month"/>
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-4">
                            <input id="expiryYear" class="form-control" placeholder="Expiry Year"/>
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-4">
                            <input id="cvc" class="form-control" placeholder="CVC"/>
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input id="cardHolderName" class="form-control" placeholder="Card Holder Name"/>
                            <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button id="realtimeSubmitButton" class="btn btn-primary">Pay Now</button>
            </div>
        </div>
        <div class="form-group" id="errors">
            <h3>Errors:</h3>
            <p>No errors.</p>
        </div>
    </div>
</form>

<script>
    var form = document.getElementById("cc-form");
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var capture = Pinch.Capture({
            publishableKey: '<?php echo $publishable_key ?>'
        });

        capture.createToken({
            cardNumber: document.getElementById("cardNumber").value,
            expiryMonth: document.getElementById("expiryMonth").value,
            expiryYear: document.getElementById("expiryYear").value,
            cvc: document.getElementById("cvc").value,
            cardHolderName: document.getElementById("cardHolderName").value
        }).then(function(result) {
            console.log("Client received token: " + result.token);
            document.getElementById("CreditCardToken").value = result.token;
            form.submit();
        }).catch(function(err) {
            document.querySelector("#errors p").innerText = JSON.stringify(err);
            console.log("Client error: " + err);
        });
    });
</script>

<script src="https://web.getpinch.com.au/capture/capture.bundle.js"></script>
</body>
</html>
