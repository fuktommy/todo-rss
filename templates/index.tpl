{include file="header.tpl" _title="TODO.RSS"}
<h1>TODO.RSS</h1>

<p>TODOを登録して、RSSリーダーに取り込むことができます。
RSSリーダーの未読管理やマーク機能を利用してTODO管理しましょう。</p>

<form method="post" action="/add" class="well form-horizontal" id="addform">
<div>
  <div class="control-group">
    <label class="control-label" for="nickname">ニックネーム</label>
    <div class="controls">
      <input name="nickname" value="{$nickname|escape}" id="nickname" class="form-control" />
      <div class="help-block">英数字と-_のみ使えます</div>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="body">TODO</label>
    <div class="controls">
      <textarea rows="5" name="body" id="body" class="form-control"></textarea>
      <div class="help-block"><ul class="unstyled">
        <li>プライバシー保護の仕組みはないので、公開できる情報のみ書いてください</li>
        <li>データは消えたり書き変わったりする可能性があります</li>
      </ul></div>
    </div>
  </div>

  <div class="form-actions">
    <button class="btn btn-primary">
      <i class="glyphicon glyphicon-pencil"></i>
      登録
    </button>
  </div>

</div>
<input type="hidden" name="token" value="{$token|escape}" />
</form>

{if $nickname && $items}
  {include file="todoitems.tpl" _nickname=$nickname _items=$items}

  {strip}
    <div class="well feed-link">
      <a href="/feed/{$nickname|escape:"url"}">
        <img src="/feed-icon-28x28.png" height="28" width="28" alt="" />
        RSSリーダーに登録してください
      </a>
    </div>
  {/strip}
{/if}

<div>
別の使い方として以下のようなアクセスをすることでTODOの追加ができます。
cronから定期的にTODOを追加するなど、いろいろな応用がありそうです。<br />
<pre><code>wget -O - -q \
  --post-data='nickname={$nickname|default:"your-nickname"|escape}&amp;body=eat+spam' \
  --header='X-Requested-With: XMLHttpRequest' \
  --referer={$config.site_top|escape}/ \
  {$config.site_top|escape}/add
</code></pre>
</div>

{include file="footer.tpl"}
