<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body>
    <div class="container">
        <h3 class="text-center">Ace of Spades server list</h3>

        <div class="col-md-10 col-md-offset-1">
            <div class="row" id="alert-container"></div>

            <div class="row text-muted">
                <div class="pull-left">
                    {{ $servers->sum('players_current') }} players /
                    {{ $servers->count() }} servers
                </div>

                <div class="pull-right">
                    [<a href="{{ request()->fullUrlWithQuery(['advanced' => ! $advanced]) }}">
                        @if ($advanced)
                            switch to basic view
                        @else
                            switch to advanced view
                        @endif
                    </a>]
                </div>
            </div>

            <div class="row">
                @include ('partials.serverlist.table')
            </div>
        </div>
    </div>
</body>
</html>
