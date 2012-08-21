<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/atomfeed.xsl" type="text/xsl"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$nickname|escape} - todo.rss</title>
  {* <subtitle>{$nickname|escape}</subtitle> *}
  <link rel="self" href="{$config.site_top}/feed/{$nickname|escape:"url"}" />
  <link rel="alternate" href="{$config.site_top}/user/{$nickname|escape:"url"}" type="text/html"/>
  <updated>{if $items}{$items[0]->date|atom_date_format}{else}{$smarty.now|atom_date_format}{/if}</updated>
  <generator>https://github.com/fuktommy/todo-rss</generator>
  <id>tag:fuktommy.com,2012:todo.rss</id>
  <author><name>{$nickname|escape}</name></author>
  <icon>{$config.site_top}/favicon.ico</icon>
{foreach from=$items item=item}
  <entry>
    <title>{$item->body|mbtruncate:140|escape}</title>
    <link rel="alternate" href="{$config.site_top}/user/{$item->nickname|escape:"url"}/{$item->date|date_format:"%s"}/{$item->id|escape:"url"}"/>
    <summary type="text">{$item->body|escape}</summary>
    <content type="html"><![CDATA[
        {$item->body|escape|nl2br}
    ]]></content>
    <published>{$item->date|atom_date_format}</published>
    <updated>{$item->date|atom_date_format}</updated>
    <author><name>{$item->nickname|escape}</name></author>
    <id>tag:fuktommy.com,2012:/{$item->nickname|escape:"url"}/{$item->date|date_format:"%s"}/{$item->id|escape:"url"}</id>
  </entry>
{/foreach}
</feed>
