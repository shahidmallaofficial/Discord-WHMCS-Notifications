<?php

///////////////////////// Provided For Free By /////////////////////////
//                                                                    //
//            //            Shahid Malla          //
//                       wwww.shahidmalla.com             //
//                                                                    //
////////////////////////////////////////////////////////////////////////

<?php

// Replace YOUR_DISCORD_WEBHOOK_URL with the URL of your Discord webhook
$webhookUrl = "YOUR_DISCORD_WEBHOOK_URL";

// Replace YOUR_WHMCS_ADMIN_URL with the URL of your WHMCS admin panel
$whmcsAdminUrl = "YOUR_WHMCS_ADMIN_URL";

// Replace YOUR_COMPANY_NAME with the name of your company
$companyName = "YOUR_COMPANY_NAME";

// Replace #ffffff with the hex code of the color you want the Discord message to be
$color = hexdec("#ffffff");

// Replace DISCORD_GROUP_ID with the ID of the Discord group you want to ping (e.g. <@&343029528563548162>)
$groupId = "DISCORD_GROUP_ID";

// (Optional) Replace YOUR_DISCORD_WEBHOOK_AVATAR with the URL of the image you want to use as the Discord webhook avatar
$avatarUrl = "YOUR_DISCORD_WEBHOOK_AVATAR";

// Register the hook function when the InvoicePaid event is triggered
add_hook('InvoicePaid', 1, function($vars) {
    // Set the message that will be sent to Discord
    $message = "An invoice has been paid by " . $vars['firstname'] . " " . $vars['lastname'] . " for " . $vars['amount'] . " " . $vars['currencycode'] . ".";

    // Send the notification to Discord
    sendDiscordMessage($message);
});

/**
 * Sends a message to the Discord channel using the webhook URL and other configuration information.
 *
 * @param string $message The message to send to the Discord channel
 */
function sendDiscordMessage($message) {
    global $webhookUrl, $whmcsAdminUrl, $companyName, $color, $groupId, $avatarUrl;

    // Create the payload that will be sent to the Discord webhook
    $payload = array(
        "username" => $companyName,
        "avatar_url" => $avatarUrl,
        "embeds" => array(
            array(
                "color" => $color,
                "description" => $message,
            )
        )
    );

    // Add the group ID to the payload if it is set
    if (!empty($groupId)) {
        $payload["content"] = $groupId;
    }

    // Encode the payload as a JSON string
    $payloadJson = json_encode($payload);

    // Send the payload to the Discord webhook
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
   
