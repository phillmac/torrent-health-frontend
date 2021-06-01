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
                    <td><?= echo $t->name; ?></td>
                    <td><?= echo $t->link; ?></td>
                    <td><?= echo formatBytes($t->size_bytes); ?></td>
                    <td><?= echo $t->seeders ?></td>
                    <td><?= echo $t->leechers ?></td>
                    <td><?= echo $t->scraped_date ?></td>
                    <td><?= echo $t->dht_peers; ?></td>
                    <td><?= echo $t->dht_scraped; ?></td>
                    <td><?= echo $t->type; ?></td>
                    <td><?= echo secondsToTime(time() - $t->scraped_date ); ?></td>



                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
