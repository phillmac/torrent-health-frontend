        <h3>
        Count: <?= count($torrents); ?>
        </h3>
        <table>
            <thead>
                <th>Info Hash</th>
                <th data-sort-method="number" data-sort-default="">Name</th>
                <th>Link</th>
                <th data-sort-method="filesize">Size</th>
                <th data-sort-method="number">Seeders</th>
                <th data-sort-method="number">Leechers</th>
                <th data-sort-method="number">Scraped</th>
                <th data-sort-method="number">DHT Peers</th>
                <th data-sort-method="number">DHT Scraped</th>
                <th>Type</th>
                <th>Last Updated</th>
            </thead>
            <tbody>
            <?php foreach($torrents as $t): ?>
                <tr>
                    <td>
                        <a href="stats-hash-table.php?hash=<?= $t->infohash; ?>"><?= $t->infohash; ?></a>
                    </td>
                    <td><?=  $t->name; ?></td>
                    <td><?=  $t->link; ?></td>
                    <td><?=  formatBytes($t->size_bytes); ?></td>
                    <td><?=  $t->seeders ?></td>
                    <td><?=  $t->leechers ?></td>
                    <td><?=  $t->scraped_date ?></td>
                    <td><?=  $t->dht_peers; ?></td>
                    <td><?=  $t->dht_scraped; ?></td>
                    <td><?=  $t->type; ?></td>
                    <td><?=  secondsToTime(time() - $t->scraped_date ); ?></td>



                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
