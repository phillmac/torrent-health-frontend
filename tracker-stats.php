<?php
    require('functions.php');
    $addresses = array (
        'errors' => upstreamAddress('ERRORS'),
        'events' => upstreamAddress('EVENTS'),
        'ignore' => upstreamAddress('IGNORE')
    );

    $tracker_data = array();

    foreach ($addresses as $addr_name => $addr_val) {
        $handle = fopen($addr_val, 'r');
        if ( !$handle ) {
            http_response_code(500);
            echo "Error";
            die();
        } else {
            $tracker_data[$addr_name]  = json_decode(stream_get_contents($handle));
        }
    }
    $trackers_list = array_unique([
        ... $tracker_data['ignore'],
        ...array_keys(get_object_vars($tracker_data['errors'])),
        ...array_keys(get_object_vars($tracker_data['events']))
    ]);
?>
<!DOCTYPE html>
<html lang="en">
    <?php require('head.php'); ?>
    <body>
        <h1>Torrent Health Tracker</h1>
        <h2>Updated: <?php echo (new \DateTime())->format('Y-m-d H:i:s e'); ?></h2>
        <table>
            <thead>
                <th>Tracker</th>
                <th>Ignored</th>
                <th data-sort-method="number">Errors</th>
                <th data-sort-method="number">Events</th>
            </thead>
            <tbody>
        <?php foreach($trackers_list as $t): ?>
            <tr>
                    <td><?= $t; ?></td>
                    <td><?= in_array($t, $tracker_data['ignore']) ? 'True': 'False'; ?></td>
                    <td><?= count($tracker_data['errors']->$t); ?></td>
                    <td><?= count($tracker_data['events']->$t); ?></td>
                </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </body>
    <!--
       <?php print_r($tracker_data, TRUE) ?>
    -->
</html>