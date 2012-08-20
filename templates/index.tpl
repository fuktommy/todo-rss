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
      <div class="help-block">プライバシー保護とかないんでよろしく</div>
    </div>
  </div>

  <div class="form-actions">
    <input type="submit" value="登録" class="btn btn-primary" />
  </div>

</div>
</form>
{include file="footer.tpl"}
