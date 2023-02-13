<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("php/deletePending.php");

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/animation.css">
    <link rel="stylesheet" href="css/stats.css">
    <link rel="stylesheet" href="css/nav.css">

    <script src="js/phpConnect.js"></script>
    <script src="js/onlineGameUtilities.js"></script>
    <script src="js/init.js"></script>
    <script src="js/drawUtility.js"></script>
    <script src="js/popup.js"></script>
    <script src="js/game.js"></script>
    <script src="js/ai.js"></script>
    <script src="js/sound.js"></script>

    <title>Super Othello</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body onload="init()">
    <nav>
        <a href="index.php">
            <img class="logo" alt="logo" src="img/logo.png">
        </a>
        <div class="nav-left">
            <?php
                if(isset($_SESSION["logged"]) && $_SESSION["logged"] == true)
                    echo '<img id="loginIcon" title="statistiche" alt="statistiche utente" src="img/user.svg">';
                else
                    echo '<img id="loginIcon" title="login" alt="login" src="img/login.svg">';
            ?>
            <img id="soundButton" alt="volume button" src="img/sound.svg">
        </div>
        <div class="nav-left">
            <span id="nav-username">
                <?php
                    if(isset($_SESSION['logged']) && $_SESSION['logged'])
                        echo $_SESSION['username'];
                ?>
            </span>
        </div>
    </nav>

    <div id="menu" class="menu-container">
        <button id="onlineButton" class="menu-button"
            <?php
                if(!isset($_SESSION['logged']) || !$_SESSION['logged'])
                    echo 'title="devi essere loggato per giocare online" disabled';
            ?> 
        >
            Partita online
        </button>
        <button id="localButton" class="menu-button">
            Modalità 2 giocatori
        </button>
        <button id="cpuButton" class="menu-button">
            Partita contro la CPU
        </button>
    </div>

    <div id="menu2" class="menu2-container">
        <button id="aboutButton" class="menu-button">
            Informazioni
        </button>
    </div>
    
    <div id="loadingBall">
    </div>
    <div id="loadingText">
        Cercando un avversario
    </div>

    <div id="gameScenario" class="game-container">
        <table class="stats" id="whitePlayer">
            <tbody>
                <tr>
                    <th id="whiteName" colspan="2">BIANCHI</th>
                </tr>
                <tr>
                    <td>Pedine</td>
                    <td id="whiteCount"></td>
                </tr>
            </tbody>
        </table>
        <canvas id="canvas"></canvas>
        <table class="stats" id="blackPlayer">
            <tbody>
                <tr>
                    <th id="blackName" colspan="2">NERI</th>
                </tr>
                <tr>
                    <td>Pedine</td>
                    <td id="blackCount"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="timer-container">
        <img alt="hourglass" id="timerImg" src="img/hourglass.svg">
        <span id="timer"></span>    
    </div>

    <div id="settingsPopup" class="overlay">
        <div class="popup">
            <button class="closeButton">
                <img alt="close" src="img/x_button.png">
            </button>
            <div class="difficultySettings">
                <h1>Difficoltà</h1> 
                <input type="range" min="1" max="5" value="2" class="slider" id="difficultySlider">
                <h2 id="difficultyName">Medio</h2>
                <button id="playButton" class="popupButton">GIOCA!</button>
            </div>
        </div>
    </div>

    <div id="accessPopup" class="overlay">
        <div class="popup">
            <button class="closeButton">
                <img alt="close" src="img/x_button.png">
            </button>
            <div class="form">
                <div>
                    <label>Nome Utente:</label>
                    <input id="username" placeholder="username" type="text" pattern="^\w{1,8}$" required>
                </div>
                <div>
                    <label>Password:</label>
                    <input id="password" placeholder="password" type="password" pattern="^\w{8,}$" required> 
                </div> 
                <button id="registerButton" class="popupButton">REGISTRATI</button>
                <button id="loginButton" class="popupButton">ACCEDI</button>
                <span id="alert" class="alertMessage"></span>
            </div>
        </div>
    </div>

    <div id="userStatsPopup" class="overlay">
        <div class="popup">
            <button class="closeButton">
                <img alt="close" src="img/x_button.png">
            </button>
            <table id="statsTable">
                <caption id="statsCaption"></caption>
                <tbody>
                    <tr>
                        <th></th><th>Vittorie</th><th>Sconfitte</th><th>Pareggi</th><th>Rateo</th>
                    </tr>
                    <tr>
                        <td>Online</td><td></td><td></td><td></td><td></td>
                    </tr>
                    <tr>
                        <td>CPU mode</td><td></td><td></td><td></td><td></td>
                    </tr>
                </tbody>    
            </table>
            <button id="logoutButton" class="popupButton">LOGOUT</button>
        </div>
    </div>
</body>
</html>