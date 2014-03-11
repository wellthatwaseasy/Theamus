<script type="text/javascript">
$(document).ready(function() {
    $("#slides").slidesjs({
        width:800,
        height:350,
        play: {
            active: false,
            effect: "slide",
            interval: 5000,
            auto: true,
            swap: true,
            pauseOnHover: false,
            restartDelay: 2500
        },
        pagination: {
            active: false,
            effect: "slide"
        },
        navigation: {
            active: false,
            effect: "slide"
        }
    });
    $("#start").click(function(e) {
        e.preventDefault();
        install_step({"where":"config"});
    })
})
</script>

<div id="slides" style="display:none; width: 800px; margin: 0 auto;">
    <img src="themes/installer/img/lists.png" alt=""/>
    <img src="themes/installer/img/admin.png" alt=""/>
    <img src="themes/installer/img/pages.png" alt=""/>
</div>

<div style="margin: 50px 0">
    <h2>What is this?</h2>
    <p class="header-p">
        <b>Theamus</b> is a web platform that will allow you to create beautiful websites
        in a modular way.
    </p>
    <p class="header-p">
        By modular, I mean that you can add and remove significant features within your
        website without doing any sort of coding.  All of what you put on here is within
        the construct of the system and everything works seamlessly.
    </p>

    <h2>Installing Theamus</h2>
    <p class="header-p">
        Installing <b>Theamus</b> is really simple.  To get started just click the button below
        and follow the bouncing ball.  You will be up and running in no time!<br />
        If you are having troubles, <a href="#">take a look here for help</a>.
    </p>

    <h2>Developing for Theamus</h2>
    <p class="header-p">
        If you are a PHP/MySQL/Javascript developer, then you must be itching to create some
        cool features for <b>Theamus</b>.<br />
        <a href="#">Take a look here to learn how to develop for <b>Theamus</b></a>
    </p>
</div>

<div style="margin: 50px auto 0; width: 100px; text-align: center;">
    <input type="button" value="Get Started Installing" class="site-purpbtn" id="start" />
</div>

<div class="clearfix"></div>