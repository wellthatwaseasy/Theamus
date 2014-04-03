<?php
$user = $tUser->user;
?>
<div id="user-result"></div>
<form class="form" id="user-form" onsubmit="return save_personal();">
    <h2 class="form-header">Your Name</h2>
    <div class="form-group">
        <label class="control-label" for="first-name">First Name</label>
        <input type="text" class="form-control" id="first-name" name="firstname" value="<?php echo $user['firstname']; ?>">
        <p class="help-block">Probably the first part of your name.</p>
    </div>
    <div class="form-group">
        <label class="control-label" for="last-name">Last Name</label>
        <input type="text" class="form-control" id="last-name" name="lastname" value="<?php echo $user['lastname']; ?>">
        <p class="help-block">Most definitely the last part of your name.</p>
    </div>

    <h2 class="form-header">Other Information</h2>
    <div class="form-group">
        <label class="control-label" for="gender">Gender</label>
        <select class="form-control" id="gender" name="gender">
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
    </div>
    <div class="form-group">
        <label class="control-label">Birthday</label>
        <?php $birthday = explode("-", $user['birthday']); ?>
        <select class="form-control form-control-inline" name="bday-m">
            <?php
            $months = array(
                "1" => "January",
                "2" => "February",
                "3" => "March",
                "4" => "April",
                "5" => "May",
                "6" => "June",
                "7" => "July",
                "8" => "August",
                "9" => "September",
                "10" => "October",
                "11" => "November",
                "12" => "December"
            );
            for ($i=1; $i<=12; $i++) {
                $selected = $i == $birthday[1] ? "selected" : "";
                echo "<option $selected value='$i'>".$months[$i]."</option>";
            }
            ?>
        </select>
        <select class="form-control form-control-inline" name="bday-d">
            <?php
            for ($i=1; $i<=31; $i++) {
                $selected = $i == $birthday[2] ? "selected" : "";
                echo "<option ".$selected." value='$i'>$i</option>";
            }
            ?>
        </select>
        <select class="form-control form-control-inline" name="bday-y">
            <?php
            for ($i=2014; $i>=1940; $i--) {
                $selected = $i == $birthday[0] ? "selected" : "";
                echo "<option ".$selected." value='$i'>$i</option>";
            }
            ?>
        </select>
    </div>

    <hr class="form-split">

    <div class="site-formsubmitrow">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>