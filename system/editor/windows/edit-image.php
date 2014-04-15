<?php

$get = filter_input_array(INPUT_GET);
$path = isset($get['path']) ? urldecode($get['path']) : "";

$image = str_replace("{t:bslash:}", "/", str_replace("{t:colon:}", ":", str_replace("{t:period:}", ".", $path)));

$width = isset($get['width']) ? $get['width'] : 0;
$height = isset($get['height']) ? $get['height'] : 0;
$align = isset($get['align']) ? $get['align'] : "";
$type = isset($get['type']) ? $get['type'] : "img";
?>
<div class="editor_window-edit-image">
    <input type="hidden" id="image_path" value="<?=$image?>" />
    <input type="hidden" id="image_type" value="<?=$type?>" />
    <span class="image"><img src="<?=$image?>" alt="" /></span>
    <div class="options">
        <div class="options-row">
            <div>
                <label class="control-label" for="alignment">Align</label>
                <select id="alignment" class="form-control">
                    <?php
                    $o = array("" => "None", "left" => "Left", "center" => "Center", "right" => "Right");
                    foreach ($o as $k => $v) {
                        $s = $align == $k ? "selected" : "";
                        echo "<option value='$k' $s>$v</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="options-row">
            <div class="form-group">
                <label class="control-label">Height</label>
                <input type="text" class="form-control" id="height" value="<?=$height?>" />
            </div>
            <div class="form-group">
                <label class="control-label">Width</label>
                <input type="text" class="form-control" id="width" value="<?=$width?>" disabled />
            </div>
            <div class="form-group">
                <label class="checkbox-inline">
                    <input type="checkbox" name="align" id="constrain" checked />
                    Constrain Proportions
                </label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>