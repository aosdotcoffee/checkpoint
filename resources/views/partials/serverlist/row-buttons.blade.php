<a
    href="{{ $server->identifier }}"
    class="btn btn-default btn-sm"
    title="Join &quot;{{ $server->name }}&quot;">
    <span class="glyphicon glyphicon-play"></span>
</a>

<button
    type="button"
    class="btn btn-default dropdown-toggle btn-sm"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false">
    <span class="caret"></span>
</button>

<ul class="dropdown-menu">
    <li>
        <a
            href="#"
            class="copy-to-clipboard"
            data-text="{{ $server->identifier }}">
            <span class="glyphicon glyphicon-copy"></span>
            Copy URI
        </a>
    </li>

    @if ($advanced)
    <li>
        <a
            href="#"
            class="copy-to-clipboard"
            data-text="{{ $server->ip_address }}:{{ $server->port }}">
            <span class="glyphicon glyphicon-copy"></span>
            Copy <code>address:port</code>
        </a>
    </li>

    <li role="separator" class="divider"></li>

    <li>
        <a href="https://bgp.tools/prefix/{{ $server->ip_address }}" target="_blank">
            <span class="glyphicon glyphicon-new-window"></span>
            View on BGP.Tools
        </a>
    </li>
    @endif
</ul>
