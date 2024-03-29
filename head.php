    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Torrent Health Tracker</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesort/5.1.0/tablesort.min.js" integrity="sha256-p3wukcf2d2jxbVnlqPDO9t4AAjnl42D2aIzrK4S0X6w=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesort/5.1.0/sorts/tablesort.number.min.js" integrity="sha256-ra1pWQ7MfuVIolZ/phcEXegs9m1ehXaCNI8cmc3gJEs=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesort/5.1.0/sorts/tablesort.filesize.min.js" integrity="sha256-qNYlSpvv2lsmkcarYkQBkUzVcHKaml/BHcGxcSNsyrU=" crossorigin="anonymous"></script>
        <style>
            body {
                font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
            }
            table tr:hover {
                background-color: #ccc;
            }
            table td {
                padding: .25em 1em;
                border-bottom: 1px solid #333;
            }
            table td:nth-child(1) {
                font-family: 'Courier New', 'Courier', monospace;
            }
        </style>
        <script>
            $(document).ready(function() {
                Array.from(document.querySelectorAll("table")).forEach(function(t) {
                    new Tablesort(t);
                })
                $('.js-is-queued').each(async function () {
                    const $el = $(this)
                    const infoHash = $el.data('infoHash')
                    $el.text(
                        (
                            await fetch('queue.php')
                                .then(resp =>resp.json())
                                .then(queue => queue?.includes(infoHash))
                        ) ? 'True' : 'False'
                    )
                })
            });
        </script>
    </head>