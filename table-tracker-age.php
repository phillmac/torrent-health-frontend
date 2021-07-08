<h3>
        Count: <?= count($torrents); ?>
        </h3>
        <table>
            <thead>
                <th>Info Hash</th>
                <th data-sort-method="number" data-sort-default="">Name</th>
                <th data-sort-method="number">Oldest</th>
                <th data-sort-method="number">Newest</th>
                <th data-sort-method="number">Average</th>
                <th data-sort-method="number">95% Age</th>
                <th>Date Oldest</th>
                <th>Date Newest</th>
                <th>Date Average</th>
                <th>Date 95% Age</th>

            </thead>
            <tbody>
            <?php
            $now = time();
            foreach($torrents as $t): ?>
                <tr>
                    <td>
                        <a href="stats-hash-table.php?hash=<?= $t->infohash; ?>"><?= $t->infohash; ?></a>
                    </td>
                    <td><?=  $t->name; ?></td>
                    <td><?=  $t->oldest ?></td>
                    <td><?=  $t->newest ?></td>
                    <td><?=  $t->average ?></td>
                    <td><?=  $t->percentile_age; ?></td>
                    <td><?=  secondsToTime($now - $t->oldest ); ?></td>
                    <td><?=  secondsToTime($now - $t->newest ); ?></td>
                    <td><?=  secondsToTime($now - $t->average ); ?></td>
                    <td><?=  secondsToTime($now - $t->percentile_age ); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
