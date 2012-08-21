<h2>{$_nickname|escape}さんのTODO</h2>
<ul class="todoitems">
{foreach from=$_items item=item}
    <li>{if time() - 600 <= strtotime($item->date)}<span class="newmark">NEW</span>{/if}
        {$item->body|escape|nl2br}
    </li>
{/foreach}
</ul>

<p>これ以上古いTODOは流されて消えました</p>
