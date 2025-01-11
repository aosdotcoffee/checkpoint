<table class="table table-responsive table-striped table-condensed table-hover">
    <thead>
        <tr>
            <th class="players">Slots</th>
            <th>Name</th>
            <th>Mode</th>
            <th>Map</th>
            @if ($advanced)
            <th>Updated</th>
            <th>Remote</th>
            <th>Authority</th>
            @endif
            <th class="actions">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($servers as $server)
            @include ('partials.serverlist.row')
        @endforeach
    </tbody>
</table>
