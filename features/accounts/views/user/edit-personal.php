<?php
$user = $tUser->user;
?>
<div id="user-result"></div>
<form class="site-form" id="user-form" onsubmit="return save_personal();">
    <div class="site-formheader">
        Your Name
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">First Name</div>
        <div class="site-forminput">
            <input type="text" name="firstname" value="<?=$user['firstname']?>" />
        </div>
        <div class="site-forminfo">
            Probably the first part of your name.
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Last Name</div>
        <div class="site-forminput">
            <input type="text" name="lastname" value="<?=$user['lastname']?>" />
        </div>
        <div class="site-forminfo">
            Most definitely the last part of your name.
        </div>
    </div>

    <div class="site-formheader">
        Other Information
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Gender</div>
        <div class="site-forminput">
            <label class="site-selectlabel">
                <select name="gender">
                    <?php
                    $genders = array("m"=>"Male", "f"=>"Female");
                    foreach ($genders as $key=>$val) {
                        if ($user['gender'] == $key) {
                            echo "<option value='".$key."' selected>".$val."</option>";
                        } else {
                            echo "<option value='".$key."'>".$val."</options>";
                        }
                    }
                    ?>
                </select>
            </label>
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Birthday</div>
        <?php
        $birthday = explode("-", $user['birthday']);
        ?>
        <div class="site-forminput">
            <label class="site-selectlabel">
                <select name="bday-m">
                    <?php
                    for ($i=1; $i<=12; $i++) {
                        $selected = $i == $birthday[1] ? "selected" : "";
                        echo "<option ".$selected." value='".$i."'>".$i."</option>";
                    }
                    ?>
                </select>
            </label>
            <label class="site-selectlabel">
                <select name="bday-d">
                    <?php
                    for ($i=1; $i<=31; $i++) {
                        $selected = $i == $birthday[2] ? "selected" : "";
                        echo "<option ".$selected." value='".$i."'>".$i."</option>";
                    }
                    ?>
                </select>
            </label>
            <label class="site-selectlabel">
                <select name="bday-y">
                    <?php
                    for ($i=2014; $i>=1940; $i--) {
                        $selected = $i == $birthday[0] ? "selected" : "";
                        echo "<option ".$selected." value='".$i."'>".$i."</option>";
                    }
                    ?>
                </select>
            </label>
        </div>
    </div>

    <hr />

    <div class="site-formsubmitrow">
        <input type="submit" value="Save" class="site-greenbtn" />
        <input type="button" value="Cancel" class="site-redbtn" />
    </div>
</form>