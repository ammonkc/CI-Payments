<html>
<head>
    <title><?php echo $page_title; ?></title>
</head>

<body>
    <h2><?php echo $page_heading; ?></h2>
    <p style="text-align:center;">Please wait while your order is being processed.<br />You will be redirected to the paypal website.</p>
    <p style="text-align:center;">If your browser does not redirect you, please<br />click the Continue button below to proceed.</p>
    
    <form id="paypal" name="paypal" method="post" action="<?php echo $gateway_url; ?>">
        <?php foreach ($fields as $name => $value): ?>
            <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /> <br />
        <?php endforeach; ?>
        <p style="text-align:center;padding-top:20px;">
        <input type="submit" value="<?php echo $submit_button; ?>" name="pp_submit" id="pp_submit" />
        </p>
    </form>
</body>