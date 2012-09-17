<h2>{$_nickname|escape}さんのTODO</h2>
<ul class="todoitems">
{foreach from=$_items item=item}
    <li>{if time() - 600 <= strtotime($item->date)}<span class="newmark">NEW</span>{/if}
        {$item->body|escape|nl2br}
        {assign var=twitter_message value="#todo "|cat:$item->body|mbtruncate:100:"…"|cat:" / RSSでTODO管理→ `$config.site_top`"}
        <a href="https://twitter.com/?status={$twitter_message|escape:"url"}" target="_blank">Twitterに書く</a>
    </li>
{/foreach}
</ul>

<p>これ以上古いTODOは流されて消えました</p>
