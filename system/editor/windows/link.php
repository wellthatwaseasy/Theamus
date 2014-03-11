<?php
$get = filter_input_array(INPUT_GET);
$text = isset($get['ltext']) ? urldecode($get['ltext']) : "";
$url = isset($get['lurl']) ? urldecode($get['lurl']) : "";
?>
<form class="editor_link-form">
	<div class="editor_link-form-row">
        <div class="editor_link-form-label">Text</div>
        <div class="editor_link-form-input">
			<input type="text" id="link-text" value="<?=$text?>" />
        </div>
	</div>
    <div class="editor_link-form-row">
        <div class="editor_link-form-label">URL</div>
        <div class="editor_link-form-input">
            <input type="text" id="weburl" value="<?=$url?>" />
        </div>
    </div>
</form>
<script>document.getElementById("link-text").focus();</script>
