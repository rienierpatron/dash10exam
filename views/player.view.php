<?php
include('views/layouts/header.view.php');
?>
<main class="game_card" id="<?= $game_type ?>">
    <h1 id="header"></h1>
    <div class="container">
        <div class="card">
            <img src="" alt="" class="logo" />
            <div class="name">
                <em id="number">#</em>
                <h2 id="name"><strong></strong></h2>
            </div>
            <div class="profile">
                <img src="" class="headshot" />
                <div class="features"></div>
            </div>
            <div class="bio">
                <div id="position" class="data">
                    <strong>Position</strong>
                </div>
                <div id="weight" class="data">
                    <strong>Weight</strong>
                </div>
                <div id="height" class="data">
                    <strong>Height</strong>
                </div>
                <div id="age" class="data">
                    <strong>Age</strong>
                </div>
            </div>
        </div>
        <div class="sidenav">
            <div class="item">Loading...</div>
        </div>
    </div>
</main>
<input type="hidden" id="game_type" value="<?= $game_type ?>">
<?php
include('views/layouts/footer.view.php');
?>
