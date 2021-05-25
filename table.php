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
                    <td><?php echo $t->name; ?></td>
                    <td><?php echo $t->link; ?></td>
                    <td><?php echo formatBytes($t->size_bytes); ?></td>
                    <td><?php echo $t->seeders ?></td>
                    <td><?php echo $t->leechers ?></td>
                    <td><?php echo $t->scraped_date ?></td>
                    <td><?php echo $t->dht_peers; ?></td>
                    <td><?php echo $t->dht_scraped; ?></td>
                    <td><?php echo $t->type; ?></td>
                    <td><?php echo secondsToTime(time() - $t->scraped_date ); ?></td>



                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
