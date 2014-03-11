<div id="result"></div>
The database should have already been created.  This step will install the
necessary table structure and data that is required to use the <b>Theamus</b>
platform.
<br /><br />
<form class="site-form" id="create-form">
    <?php if ($Installer->check_database()): ?>
    <div class="site-formheader">Existing Database Detected</div>
    <div class="site-formrow">
        <div class="site-formlabel">Reset Database</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="reset" id="reset">
                <label class="site-switchlabel yn" for="reset">
                    <span class="site-switchinner"></span>
                    <span class="site-switchswitch"></span>
                </label>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="site-forminfo">
            Doing this will erase all of the data you currently have and reload the
            Theamus defaults.
        </div>
    </div>
    <?php endif; ?>
    <div class="site-formheader">Customization</div>
    <div class="site-formrow">
        <div class="site-formlabel">Table Prefix</div>
        <div class="site-forminput">
            <input type="text" name="prefix" id="prefix" maxlength="10"
                   autocomplete="off" autocorrect="off" autocapitalize="off"
                   spellcheck="off" value="tm_" style="width: 75px;"/>
        </div>
        <div class="site-forminfo">
            This is a short prefix that comes before a table name for the
            Theamus system.  It's required to differentiate between system and
            installed features.
        </div>
    </div>

    <hr />

    <div class="site-formsubmitrow">
        <input type="submit" value="Create and Go Next" class="site-greenbtn" />
    </div>
</form>
