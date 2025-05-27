<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Jeu du serpent classique revisité avec des niveaux progressifs et des obstacles">
    <meta name="theme-color" content="#1a1a2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Snake Game - Bienvenue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4ecca3;
            --secondary-color: #ff4444;
            --dark-bg: #1a1a2e;
            --light-bg: #f0f0f0;
            --dark-text: #232323;
            --light-text: #ffffff;
            --neon-glow: 0 0 10px var(--primary-color),
                         0 0 20px var(--primary-color),
                         0 0 30px var(--primary-color);
            --card-bg-dark: rgba(26, 26, 46, 0.3);
            --card-bg-light: rgba(240, 240, 240, 0.8);
        }
        
        [data-theme="light"] {
            --primary-color: #2a9d8f;
            --secondary-color: #e63946;
            --bg-color: var(--light-bg);
            --text-color: var(--dark-text);
            --card-bg: var(--card-bg-light);
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="dark"] {
            --primary-color: #4ecca3;
            --secondary-color: #ff4444;
            --bg-color: var(--dark-bg);
            --text-color: var(--light-text);
            --card-bg: var(--card-bg-dark);
            --shadow-color: rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-color, var(--dark-bg)), var(--bg-color, #16213e));
            font-family: 'Poppins', sans-serif;
            color: var(--text-color, var(--light-text));
            overflow-x: hidden;
            position: relative;
            perspective: 1000px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .parallax-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: radial-gradient(circle at center, rgba(78, 204, 163, 0.1) 0%, transparent 70%);
            transform-style: preserve-3d;
            pointer-events: none;
        }

        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            mix-blend-mode: screen;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
            background: var(--card-bg, rgba(26, 26, 46, 0.3));
            border-radius: 15px;
            box-shadow: 0 8px 32px var(--shadow-color, rgba(0, 0, 0, 0.3));
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .header {
            text-align: center;
            padding: 1rem 0;
            margin-bottom: 1rem;
            position: relative;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 15px var(--shadow-color, rgba(0, 0, 0, 0.2));
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .header-decoration {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                var(--primary-color) 50%, 
                transparent 100%
            );
            opacity: 0.7;
        }

        .game-title {
            font-family: 'Press Start 2P', cursive;
            font-size: 1.2rem;
            color: var(--primary-color);
            text-shadow: var(--neon-glow);
            margin-bottom: 0.25rem;
            animation: neonPulse 2s ease-in-out infinite;
            position: relative;
        }

        .game-title::after {
            content: 'SNAKE GAME';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: transparent;
            text-shadow: 0 0 20px rgba(78, 204, 163, 0.5);
            filter: blur(8px);
            z-index: -1;
            animation: neonBlur 2s ease-in-out infinite alternate;
        }

        .subtitle {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .game-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 0.5rem;
            box-shadow: 0 0 30px var(--shadow-color, rgba(0, 0, 0, 0.3)),
                        inset 0 0 15px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }

        .game-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 40px rgba(0, 0, 0, 0.4),
                        inset 0 0 15px rgba(255, 255, 255, 0.2);
        }

        .game-info {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 300px;
            margin-bottom: 0.25rem;
            padding: 0.25rem;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #score, #high-score, #level {
            font-family: 'Press Start 2P', cursive;
            font-size: 0.7rem;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
        }

        .obstacle {
            position: absolute;
            background: #2e8b57;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(46, 139, 87, 0.8);
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .level-up {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(78, 204, 163, 0.9);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            display: none;
            z-index: 10;
            box-shadow: 0 0 20px rgba(78, 204, 163, 0.5);
            animation: pulse 1s infinite, slideIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }

        #game-board {
            width: 250px;
            height: 250px;
            background: rgba(255, 68, 68, 0.8);
            border: 2px solid var(--light-text);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(255, 68, 68, 0.4),
                        inset 0 0 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .snake-part {
            width: 15px;
            height: 15px;
            background: var(--primary-color);
            position: absolute;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(78, 204, 163, 0.5);
            transition: all 0.06s cubic-bezier(0.2, 0.8, 0.2, 1);
            transform-origin: center;
            will-change: transform, left, top;
            backface-visibility: hidden;
            perspective: 1000px;
        }

        .snake-part:first-child {
            background: #2e8b57;
            box-shadow: 0 0 10px rgba(46, 139, 87, 0.8);
            z-index: 2;
            transition: all 0.04s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        #apple {
            width: 15px;
            height: 15px;
            background: var(--light-text);
            position: absolute;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            animation: pulse 1s infinite;
        }

        .controls {
            margin-top: 0.5rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease;
        }
        
        /* Contrôles tactiles */
        .touch-controls {
            display: none;
            margin-top: 1rem;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 0.5rem;
            width: 150px;
            height: 150px;
        }
        
        .touch-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        .touch-btn:active {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(0.95);
        }
        
        .touch-btn.up {
            grid-column: 2;
            grid-row: 1;
        }
        
        .touch-btn.left {
            grid-column: 1;
            grid-row: 2;
        }
        
        .touch-btn.right {
            grid-column: 3;
            grid-row: 2;
        }
        
        .touch-btn.down {
            grid-column: 2;
            grid-row: 3;
        }
        
        @media (max-width: 768px) {
            .touch-controls {
                display: grid;
            }
        }
        
        /* Switch thème */
        .theme-switch {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--card-bg, rgba(26, 26, 46, 0.3));
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 10px var(--shadow-color, rgba(0, 0, 0, 0.3));
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease;
        }
        
        .theme-switch i {
            color: var(--primary-color);
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .controls-title {
            font-family: 'Press Start 2P', cursive;
            font-size: 0.7rem;
            margin-bottom: 0.25rem;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
        }

        .speed-selector {
            margin: 1rem 0;
            text-align: center;
        }

        .speed-selector label {
            display: block;
            margin-bottom: 0.5rem;
            font-family: 'Press Start 2P', cursive;
            font-size: 0.7rem;
            color: var(--primary-color);
        }

        .speed-selector select {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light-text);
            border: 1px solid var(--primary-color);
            padding: 0.5rem;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
        }

        .speed-selector select:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .speed-selector select option {
            background: var(--dark-bg);
            color: var(--light-text);
        }

        .keys {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .key {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem;
            border-radius: 8px;
            font-family: 'Press Start 2P', cursive;
            font-size: 0.6rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .key:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        #start-btn {
            font-family: 'Press Start 2P', cursive;
            font-size: 0.7rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(45deg, var(--primary-color), #2e8b57);
            color: var(--dark-bg);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(78, 204, 163, 0.3),
                       inset 0 0 10px rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.75rem;
        }

        #start-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: rotate(45deg);
            transition: 0.5s;
        }

        #start-btn:hover {
            transform: scale(1.05);
            background: #2e8b57;
            box-shadow: 0 0 20px rgba(78, 204, 163, 0.5);
        }

        #start-btn:hover::before {
            left: 100%;
        }

        .game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(26, 26, 46, 0.95);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            display: none;
            z-index: 100;
            border: 2px solid var(--primary-color);
            box-shadow: 0 0 20px rgba(78, 204, 163, 0.3);
            backdrop-filter: blur(5px);
        }

        .game-over {
            animation: fadeIn 0.5s ease-out;
        }
        
        .game-over h2 {
            color: var(--secondary-color);
            font-family: 'Press Start 2P', cursive;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(255, 68, 68, 0.5);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        @keyframes neonPulse {
            0% { text-shadow: var(--neon-glow); }
            50% { text-shadow: 0 0 20px var(--primary-color),
                              0 0 40px var(--primary-color),
                              0 0 60px var(--primary-color); }
            100% { text-shadow: var(--neon-glow); }
        }

        @keyframes neonBlur {
            0% { filter: blur(8px); opacity: 0.5; }
            100% { filter: blur(12px); opacity: 0.8; }
        }

        @keyframes snakeMove {
            0% { transform: scale(0.95) rotate(0deg); }
            50% { transform: scale(1.02) rotate(2deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        @keyframes snakeHeadMove {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .speed-selector {
            margin: 1rem 0;
            text-align: center;
        }

        .speed-selector label {
            display: block;
            margin-bottom: 0.5rem;
            font-family: 'Press Start 2P', cursive;
            font-size: 0.7rem;
            color: var(--primary-color);
        }

        .speed-selector select {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light-text);
            border: 1px solid var(--primary-color);
            padding: 0.5rem;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
        }

        .speed-selector select:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .speed-selector select option {
            background: var(--dark-bg);
            color: var(--light-text);
        }

        @media (max-width: 600px) {
            .game-container {
                padding: 1rem;
            }

            #game-board {
                width: 250px;
                height: 250px;
            }

            .game-title {
                font-size: 2rem;
            }

            .keys {
                flex-wrap: wrap;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>
<body data-theme="dark">
    <div class="parallax-bg"></div>
    <div id="particles-js"></div>
    <button class="theme-switch" aria-label="Changer de thème" title="Changer de thème">
        <i class="fas fa-sun"></i>
    </button>
    <div class="container">
        <header class="header">
            <h1 class="game-title">SNAKE GAME</h1>
            <p class="subtitle">Le jeu classique du serpent revisité</p>
            <div class="header-decoration"></div>
        </header>

        <div class="game-container">
            <div class="game-info">
                <div id="score">SCORE: 0</div>
                <div id="level">NIVEAU: 1</div>
                <div id="high-score">MEILLEUR: 0</div>
            </div>

            <div id="game-board">
                <div id="apple"></div>
                <div class="game-over">
                    <h2>GAME OVER</h2>
                    <p>Score final: <span class="final-score">0</span></p>
                    <p>Niveau atteint: <span class="final-level">1</span></p>
                </div>
                <div class="level-up">
                    <h2>NIVEAU SUIVANT!</h2>
                    <p>Félicitations!</p>
                    <p>Niveau <span class="new-level">2</span></p>
                </div>
            </div>

            <div class="controls">
                <h3 class="controls-title">CONTRÔLES</h3>
                <div class="speed-selector">
                    <label for="snake-speed">VITESSE DU SERPENT:</label>
                    <select id="snake-speed" aria-label="Sélectionner la vitesse du jeu">
                        <option value="120">Lente</option>
                        <option value="80" selected>Normale</option>
                        <option value="50">Rapide</option>
                        <option value="30">Très Rapide</option>
                    </select>
                </div>
                <div class="keys" aria-label="Contrôles clavier">
                    <div class="key" aria-label="Flèche gauche">←</div>
                    <div class="key" aria-label="Flèche haut">↑</div>
                    <div class="key" aria-label="Flèche droite">→</div>
                    <div class="key" aria-label="Flèche bas">↓</div>
                </div>
                <div class="touch-controls" aria-label="Contrôles tactiles">
                    <div class="touch-btn up" aria-label="Haut"><i class="fas fa-chevron-up"></i></div>
                    <div class="touch-btn left" aria-label="Gauche"><i class="fas fa-chevron-left"></i></div>
                    <div class="touch-btn right" aria-label="Droite"><i class="fas fa-chevron-right"></i></div>
                    <div class="touch-btn down" aria-label="Bas"><i class="fas fa-chevron-down"></i></div>
                </div>
                <button id="start-btn" aria-label="Démarrer une nouvelle partie">NOUVELLE PARTIE</button>
            </div>
        </div>
    </div>

    <!-- Sons du jeu -->
    <audio id="eat-sound" preload="auto">
        <source src="https://assets.codepen.io/21542/snap.mp3" type="audio/mpeg">
    </audio>
    <audio id="game-over-sound" preload="auto">
        <source src="https://assets.codepen.io/21542/lose.mp3" type="audio/mpeg">
    </audio>
    <audio id="level-up-sound" preload="auto">
        <source src="https://assets.codepen.io/21542/win.mp3" type="audio/mpeg">
    </audio>
    
    <script>
        // Gestion du thème
        const themeSwitch = document.querySelector('.theme-switch');
        const themeIcon = themeSwitch.querySelector('i');
        const body = document.body;
        
        // Vérifier s'il y a un thème enregistré
        const savedTheme = localStorage.getItem('snakeTheme') || 'dark';
        body.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        themeSwitch.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('snakeTheme', newTheme);
            
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fas fa-sun';
            } else {
                themeIcon.className = 'fas fa-moon';
            }
        }
        
        // Configuration des particules
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 50,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#4ecca3'
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: true
                },
                size: {
                    value: 3,
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#4ecca3',
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: true,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 0.5
                        }
                    }
                }
            }
        });

        // Code du jeu existant
        const gameBoard = document.getElementById('game-board');
        const apple = document.getElementById('apple');
        const scoreElement = document.getElementById('score');
        const highScoreElement = document.getElementById('high-score');
        const startBtn = document.getElementById('start-btn');
        const gameOverScreen = document.querySelector('.game-over');
        const finalScoreElement = document.querySelector('.final-score');
        const finalLevelElement = document.querySelector('.final-level');
        const levelUpScreen = document.querySelector('.level-up');
        const newLevelSpan = document.querySelector('.new-level');
        
        // Sons
        const eatSound = document.getElementById('eat-sound');
        const gameOverSound = document.getElementById('game-over-sound');
        const levelUpSound = document.getElementById('level-up-sound');
        
        // Contrôles tactiles
        const touchControls = {
            up: document.querySelector('.touch-btn.up'),
            left: document.querySelector('.touch-btn.left'),
            right: document.querySelector('.touch-btn.right'),
            down: document.querySelector('.touch-btn.down')
        };

        let score = 0;
        let highScore = localStorage.getItem('snakeHighScore') || 0;
        let direction = 'right';
        let snakeBody = [];
        let appleX = 0;
        let appleY = 0;
        let gameInterval;
        let isPaused = false;
        let currentLevel = 1;
        let obstacles = [];
        let levelUpScore = 50;
        let baseSpeed = parseInt(document.getElementById('snake-speed').value);
        const speedSelector = document.getElementById('snake-speed');

        // Mettre à jour la vitesse quand l'utilisateur change la sélection
        speedSelector.addEventListener('change', function() {
            baseSpeed = parseInt(this.value);
            if (gameInterval) {
                clearInterval(gameInterval);
                gameInterval = setInterval(moveSnake, Math.max(baseSpeed - (currentLevel * 5), 40));
            }
        });

        highScoreElement.textContent = `MEILLEUR: ${highScore}`;
        document.getElementById('level').textContent = `NIVEAU: ${currentLevel}`;

        function createSnakePart(x, y) {
            const part = document.createElement('div');
            part.className = 'snake-part';
            part.style.left = x + 'px';
            part.style.top = y + 'px';
            gameBoard.appendChild(part);
            return part;
        }

        function placeApple() {
            appleX = Math.floor(Math.random() * 19) * 15;
            appleY = Math.floor(Math.random() * 19) * 15;
            
            const isOnSnake = snakeBody.some(part => {
                const partX = parseInt(part.style.left);
                const partY = parseInt(part.style.top);
                return partX === appleX && partY === appleY;
            });

            if (isOnSnake) {
                placeApple();
                return;
            }

            apple.style.left = appleX + 'px';
            apple.style.top = appleY + 'px';
        }

        let queuedDirection = null;

        function moveSnakePart(part, x, y, isHead = false) {
            requestAnimationFrame(() => {
                part.style.left = x + 'px';
                part.style.top = y + 'px';
                part.style.animation = isHead ? 'snakeHeadMove 0.06s ease-out' : 'snakeMove 0.08s ease-out';
            });
        }

        function handleKeyPress(e) {
            if (isPaused && e.key !== ' ') return;

            switch(e.key) {
                case 'ArrowRight':
                    if (direction !== 'left') queuedDirection = 'right';
                    break;
                case 'ArrowLeft':
                    if (direction !== 'right') queuedDirection = 'left';
                    break;
                case 'ArrowUp':
                    if (direction !== 'down') queuedDirection = 'up';
                    break;
                case 'ArrowDown':
                    if (direction !== 'up') queuedDirection = 'down';
                    break;
                case ' ':
                    isPaused = !isPaused;
                    break;
            }
        }

        function moveSnake() {
            if (isPaused) return;

            if (queuedDirection) {
                direction = queuedDirection;
                queuedDirection = null;
            }

            const head = snakeBody[0];
            let newX = parseInt(head.style.left);
            let newY = parseInt(head.style.top);

            switch(direction) {
                case 'right': newX += 15; break;
                case 'left': newX -= 15; break;
                case 'up': newY -= 15; break;
                case 'down': newY += 15; break;
            }

            if (newX < 0) newX = 285;
            if (newX > 285) newX = 0;
            if (newY < 0) newY = 285;
            if (newY > 285) newY = 0;

            const selfCollision = snakeBody.some((part, index) => {
                if (index === 0) return false;
                const partX = parseInt(part.style.left);
                const partY = parseInt(part.style.top);
                return newX === partX && newY === partY;
            });

            if (selfCollision) {
                gameOver();
                return;
            }

            for (let i = snakeBody.length - 1; i > 0; i--) {
                const prevPart = snakeBody[i - 1];
                const currentPart = snakeBody[i];
                moveSnakePart(currentPart, parseInt(prevPart.style.left), parseInt(prevPart.style.top));
            }

            moveSnakePart(head, newX, newY, true);
            checkCollision();
        }

        function createObstacle(x, y, width, height) {
            const obstacle = document.createElement('div');
            obstacle.className = 'obstacle';
            obstacle.style.left = x + 'px';
            obstacle.style.top = y + 'px';
            obstacle.style.width = width + 'px';
            obstacle.style.height = height + 'px';
            gameBoard.appendChild(obstacle);
            obstacles.push(obstacle);
        }

        function setupLevel() {
            // Supprimer les obstacles existants
            obstacles.forEach(obstacle => obstacle.remove());
            obstacles = [];

            // Configurer les obstacles selon le niveau
            switch(currentLevel) {
                case 2:
                    createObstacle(240, 240, 20, 100);
                    break;
                case 3:
                    createObstacle(160, 160, 20, 100);
                    createObstacle(320, 240, 100, 20);
                    break;
                case 4:
                    createObstacle(120, 120, 20, 260);
                    createObstacle(360, 120, 20, 260);
                    break;
                case 5:
                    createObstacle(100, 100, 300, 20);
                    createObstacle(100, 380, 300, 20);
                    break;
            }
        }

        function checkLevelUp() {
            if (score >= levelUpScore) {
                currentLevel++;
                levelUpScore += currentLevel * 50;
                
                // Afficher l'animation de niveau supérieur
                newLevelSpan.textContent = currentLevel;
                levelUpScreen.style.display = 'block';
                
                // Jouer le son de niveau supérieur
                if (levelUpSound) {
                    levelUpSound.currentTime = 0;
                    levelUpSound.play().catch(e => console.log('Erreur audio:', e));
                }
                
                // Pause temporaire
                isPaused = true;
                
                // Configurer le nouveau niveau
                setupLevel();
                
                // Mettre à jour l'affichage du niveau
                document.getElementById('level').textContent = `NIVEAU: ${currentLevel}`;
                
                // Reprendre après 2 secondes
                setTimeout(() => {
                    levelUpScreen.style.display = 'none';
                    isPaused = false;
                }, 2000);
                
                // Augmenter la vitesse
                clearInterval(gameInterval);
                const newSpeed = Math.max(baseSpeed - (currentLevel * 5), 40);
                gameInterval = setInterval(moveSnake, newSpeed);
            }
        }

        function checkCollision() {
            const head = snakeBody[0];
            const headX = parseInt(head.style.left);
            const headY = parseInt(head.style.top);

            // Vérifier la collision avec les obstacles
            const obstacleCollision = obstacles.some(obstacle => {
                const obstacleX = parseInt(obstacle.style.left);
                const obstacleY = parseInt(obstacle.style.top);
                const obstacleWidth = parseInt(obstacle.style.width);
                const obstacleHeight = parseInt(obstacle.style.height);

                return headX < (obstacleX + obstacleWidth) &&
                       (headX + 20) > obstacleX &&
                       headY < (obstacleY + obstacleHeight) &&
                       (headY + 20) > obstacleY;
            });

            if (obstacleCollision) {
                gameOver();
                return;
            }

            if (headX === appleX && headY === appleY) {
                score += 10;
                scoreElement.textContent = `SCORE: ${score}`;
                
                // Jouer le son de manger
                if (eatSound) {
                    eatSound.currentTime = 0;
                    eatSound.play().catch(e => console.log('Erreur audio:', e));
                }
                
                const lastPart = snakeBody[snakeBody.length - 1];
                const newPart = createSnakePart(
                    parseInt(lastPart.style.left),
                    parseInt(lastPart.style.top)
                );
                snakeBody.push(newPart);
                
                placeApple();
                checkLevelUp();
            }
        }

        function gameOver() {
            clearInterval(gameInterval);
            isPaused = true;
            
            // Jouer le son de game over
            if (gameOverSound) {
                gameOverSound.currentTime = 0;
                gameOverSound.play().catch(e => console.log('Erreur audio:', e));
            }
            
            if (score > highScore) {
                highScore = score;
                localStorage.setItem('snakeHighScore', highScore);
                highScoreElement.textContent = `MEILLEUR: ${highScore}`;
            }

            finalScoreElement.textContent = score;
            finalLevelElement.textContent = currentLevel;
            gameOverScreen.style.display = 'block';
            
            // Animation de game over
            gameOverScreen.style.animation = 'fadeIn 0.5s ease-in-out';
        }

        function updateBaseSpeed() {
            baseSpeed = parseInt(speedSelector.value);
            if (gameInterval) {
                clearInterval(gameInterval);
                gameInterval = setInterval(moveSnake, Math.max(baseSpeed - (currentLevel * 5), 40));
            }
        }

        speedSelector.addEventListener('change', updateBaseSpeed);

        // Fonction pour activer/désactiver le son
        let soundEnabled = localStorage.getItem('snakeSoundEnabled') === 'false' ? false : true;
        
        function toggleSound() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('snakeSoundEnabled', soundEnabled);
            document.querySelectorAll('audio').forEach(audio => {
                audio.muted = !soundEnabled;
            });
        }
        
        // Initialiser l'état du son
        document.querySelectorAll('audio').forEach(audio => {
            audio.muted = !soundEnabled;
        });
        
        function startGame() {
            snakeBody.forEach(part => part.remove());
            obstacles.forEach(obstacle => obstacle.remove());
            snakeBody = [];
            obstacles = [];
            gameOverScreen.style.display = 'none';
            gameOverScreen.style.animation = '';
            
            // Mettre à jour la vitesse de base au début de chaque partie
            baseSpeed = parseInt(speedSelector.value);
            
            score = 0;
            currentLevel = 1;
            levelUpScore = 50;
            scoreElement.textContent = 'SCORE: 0';
            document.getElementById('level').textContent = 'NIVEAU: 1';
            direction = 'right';
            queuedDirection = null;
            isPaused = false;

            const initialX = 150;
            const initialY = 150;
            const head = createSnakePart(initialX, initialY);
            snakeBody.push(head);

            placeApple();
            setupLevel();

            clearInterval(gameInterval);
            baseSpeed = parseInt(speedSelector.value);
            gameInterval = setInterval(moveSnake, Math.max(baseSpeed - (currentLevel * 5), 40));
        }

        // Gestionnaires d'événements pour les contrôles tactiles
        touchControls.up.addEventListener('click', () => {
            if (direction !== 'down') queuedDirection = 'up';
        });
        
        touchControls.left.addEventListener('click', () => {
            if (direction !== 'right') queuedDirection = 'left';
        });
        
        touchControls.right.addEventListener('click', () => {
            if (direction !== 'left') queuedDirection = 'right';
        });
        
        touchControls.down.addEventListener('click', () => {
            if (direction !== 'up') queuedDirection = 'down';
        });
        
        // Support des événements tactiles pour une meilleure réactivité
        touchControls.up.addEventListener('touchstart', (e) => {
            e.preventDefault();
            if (direction !== 'down') queuedDirection = 'up';
        });
        
        touchControls.left.addEventListener('touchstart', (e) => {
            e.preventDefault();
            if (direction !== 'right') queuedDirection = 'left';
        });
        
        touchControls.right.addEventListener('touchstart', (e) => {
            e.preventDefault();
            if (direction !== 'left') queuedDirection = 'right';
        });
        
        touchControls.down.addEventListener('touchstart', (e) => {
            e.preventDefault();
            if (direction !== 'up') queuedDirection = 'down';
        });
        
        document.addEventListener('keydown', handleKeyPress);
        startBtn.addEventListener('click', startGame);
        startGame();

        // Effet de parallaxe
        document.addEventListener('mousemove', (e) => {
            const parallaxBg = document.querySelector('.parallax-bg');
            const mouseX = (e.clientX / window.innerWidth - 0.5) * 20;
            const mouseY = (e.clientY / window.innerHeight - 0.5) * 20;
            
            parallaxBg.style.transform = `translate(${mouseX}px, ${mouseY}px)`;
        });
        
        // Amélioration de l'accessibilité - gestion des focus
        const focusableElements = document.querySelectorAll('button, select, .touch-btn');
        focusableElements.forEach(el => {
            el.addEventListener('focus', () => {
                el.style.outline = `2px solid var(--primary-color)`;
                el.style.boxShadow = `0 0 10px var(--primary-color)`;
            });
            
            el.addEventListener('blur', () => {
                el.style.outline = '';
                el.style.boxShadow = '';
            });
        });
        
        // Ajouter un bouton de son
        const soundButton = document.createElement('button');
        soundButton.className = 'theme-switch sound-toggle';
        soundButton.style.top = '60px';
        soundButton.setAttribute('aria-label', 'Activer/désactiver le son');
        soundButton.setAttribute('title', 'Activer/désactiver le son');
        soundButton.innerHTML = `<i class="fas ${soundEnabled ? 'fa-volume-up' : 'fa-volume-mute'}"></i>`;
        document.body.appendChild(soundButton);
        
        soundButton.addEventListener('click', () => {
            toggleSound();
            soundButton.innerHTML = `<i class="fas ${soundEnabled ? 'fa-volume-up' : 'fa-volume-mute'}"></i>`;
        });
        
        // Ajouter un service worker pour une meilleure expérience hors ligne
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.log('Service worker registration failed:', err);
                });
            });
        }
    </script>
</body>
</html>
