<?php

$get = filter_input_array(INPUT_GET);
$path = isset($get['path']) ? urldecode($get['path']) : "";

$image = str_replace("{t:bslash:}", "/", str_replace("{t:colon:}", ":", str_replace("{t:period:}", ".", $path)));

$width = isset($get['width']) ? $get['width'] : 0;
$height = isset($get['height']) ? $get['height'] : 0;
$align = isset($get['align']) ? $get['align'] : "";
?>
<div class="editor_window-edit-image">
    <input type="hidden" id="image_path" value="<?=$image?>" />
    <span class="image"><img src="<?=$image?>" alt="" /></span>
    <div class="options">
        <div class="options-row">
            <div class="options-label">Align</div>
            <div class="options-input">
                <label>
                    <select id="alignment">
                        <?php
                        $o = array("" => "None", "left" => "Left", "center" => "Center", "right" => "Right");
                        foreach ($o as $k => $v) {
                            $s = $align == $k ? "selected" : "";
                            echo "<option value='$k' $s>$v</option>";
                        }
                        ?>
                    </select>
                </label>
            </div>
        </div>

        <div class="options-row">
            <div class="left size">
                Height<br />
                <input type="text" id="height" value="<?=$height?>" />
            </div>
            <div class="left size">
                Width<br />
                <input type="text" id="width" value="<?=$width?>" disabled />
            </div>
            <div class="clearfix"></div>
            <div class="constrain">
                <div class="cboxwrapper cbox">
                    <input type="checkbox" name="align" class="switchcbox"
                      id="constrain" checked />
                    <label class="switchlabel yn" for="constrain">
                      <span class="switchinner"></span>
                      <span class="switchswitch"></span>
                    </label>
                </div>
                <label for="constrain">Constrain Proportions</label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>