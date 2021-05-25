<?php
    require('functions.php');
    
    use Exceptions\FilterException as FilterException;

    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    if ( !$handle ) {
        http_response_code(500);
        echo 'Error';
    }  else {
        try {
            $torrents = getFiltered(
                handleGetFormatted($handle),
                buildFilters()
            );
        } catch (FilterException $e) {
            http_response_code(400);
            echo $e->getMessage(), "\n";
        }
?>

<!DOCTYPE html>
<html lang="en">
    <?php require('head.php'); ?>
    <body>
        <h1>Torrent Health Tracker</h1>
        <h2>Updated: <?php echo (new \DateTime())->format('Y-m-d H:i:s e'); ?></h2>
        <?php require('table.php'); ?>
    </body>
</html>

<?php
    }
?>






