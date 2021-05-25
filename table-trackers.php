        <table>
            <thead>
                <th>Tracker</th>
                <th data-sort-method="number">Seeders</th>
                <th data-sort-method="number">Leechers</th>
                <th data-sort-method="number">Scraped</th>
                <th>Last Updated</th>
            </thead>
            <tbody>
            <?php foreach($torrent->trackerData as $t ->$stats): ?>
                <tr>
                    <td><?= $t; ?></td>
                    <td><?= $stats->complete ?></td>
                    <td><?= $stats->incomplete ?></td>
                    <td><?= $stats->scraped_date ?></td>
                    <td><?= secondsToTime(time() - $stats->scraped_date ); ?></td>



                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
