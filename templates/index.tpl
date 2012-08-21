{include file="header.tpl" _title="TODO.RSS"}
<h1>TODO.RSS</h1>

<form method="post" action="/add" class="well form-horizontal">
<div>
  <div class="control-group">
    <label class="control-label" for="nickname">ニックネーム</label>
    <div class="controls">
      <input name="nickname" value="{$nickname|escape}" id="nickname" />
      <div class="help-block">英数字と-_のみ使えます</div>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="body">TODO</label>
    <div class="controls">
      <textarea rows="5" name="body" id="body" class="input-xxlarge"></textarea>
      <div class="help-block"><ul class="unstyled">
        <li>プライバシー保護の仕組みはないので、公開できる情報のみ書いてください</li>
        <li>データは消えたり書き変わったりする可能性があります</li>
      </ul></div>
    </div>
  </div>

  <div class="form-actions">
    <input type="submit" value="登録" class="btn btn-primary" />
  </div>

</div>
</form>

{if $nickname && $items}
    {include file="todoitems.tpl" _nickname=$nickname _items=$items}
{/if}

{include file="footer.tpl"}
