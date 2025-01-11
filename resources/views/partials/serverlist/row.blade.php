<tr>
    <td @class([
        'text-center',
        'text-danger' => $server->players_current === $server->players_max,
    ])>
        {{ $server->players_current }}/{{ $server->players_max }}
    </td>
    <td>
        <div class="famfamfam-flags {{ strtolower($server->country) }}"></div>
        {{ $server->name }}
        @if ($server->game_version !== '0.75')
            <span class="label label-default">{{ $server->game_version }}</span>
        @endif
    </td>
    <td>{{ $server->gamemode }}</td>
    <td>{{ $server->map }}</td>
    @if ($advanced)
    <td>{{ $server->last_updated->shortRelativeDiffForHumans() }}</td>
    <td>{{ $server->remote->short_name }}</td>
    <td>
        @if ($server->authority)
            {{ $server->authority->name }}
        @else
            <i class="text-muted">n/a</i>
        @endif
    </td>
    @endif
    <td style="width: 0%">
        <div class="pull-right btn-group">
            @include ('partials.serverlist.row-buttons')
        </div>
    </td>
</tr>
