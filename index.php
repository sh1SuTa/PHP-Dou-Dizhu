<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>斗地主</title>
    <style>
        body {
            background: linear-gradient(135deg, #1a4d1a 0%, #0d330d 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            font-family: 'Microsoft YaHei', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #fff;
            font-size: 36px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 30px;
            font-size: 18px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            margin: 0 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,107,107,0.4);
        }
        .btn:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }
        .turn-indicator {
            text-align: center;
            margin-bottom: 20px;
        }
        .turn-indicator span {
            padding: 10px 30px;
            background: rgba(255,215,0,0.3);
            color: #ffd700;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(255,215,0,0.2);
        }
        .ai-section {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-bottom: 20px;
        }
        .ai-box {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            flex: 1;
            min-width: 200px;
        }
        .ai-box h3 {
            color: #ccc;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .ai-card-back {
            width: 45px;
            height: 65px;
            border-radius: 6px;
            background: url('img/BeiMian.jpg') no-repeat center/contain;
            display: inline-block;
            margin: 2px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            transition: transform 0.3s;
        }
        .ai-card-back.thinking {
            animation: cardMove 0.8s ease-in-out infinite;
        }
        .last-play-section {
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .last-play-section h3 {
            color: #ffd700;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .last-cards-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            min-height: 100px;
            align-items: center;
        }
        .last-card {
            width: 60px;
            height: 85px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            animation: cardAppear 0.5s ease-out;
        }
        .last-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pass-text {
            color: #ccc;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
        }
        .bottom-section {
            background: rgba(255,215,0,0.2);
            border: 2px solid rgba(255,215,0,0.5);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .bottom-section h2 {
            color: #ffd700;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .bottom-cards {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .player-section {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }
        .player-header {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .player-header span {
            background: #ff6b6b;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
        }
        .card {
            width: 60px;
            height: 85px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
            border: 3px solid transparent;
            position: relative;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card.selected {
            border-color: #ffd700;
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        @keyframes cardAppear {
            0% {
                transform: translateY(-50px) rotate(-10deg) scale(0.8);
                opacity: 0;
            }
            60% {
                transform: translateY(5px) rotate(2deg) scale(1.05);
                opacity: 1;
            }
            100% {
                transform: translateY(0) rotate(0) scale(1);
                opacity: 1;
            }
        }
        @keyframes cardMove {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(255,215,0,0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(255,215,0,0);
            }
        }
        .thinking-indicator {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🃏 斗地主</h1>

        <div class="btn-container">
            <button class="btn" onclick="initGame()">开始游戏</button>
            <button class="btn" onclick="getState()">刷新状态</button>
            <button class="btn" onclick="playCards()">出牌</button>
            <button class="btn" onclick="pass()">不要</button>
        </div>

        <div class="turn-indicator">
            <span id="turnInfo">等待开始...</span>
        </div>

        <div class="ai-section">
            <div class="ai-box">
                <h3>🤖 AI1 - <span id="ai1Count">0</span>张牌</h3>
                <div id="ai1Cards"></div>
            </div>
            <div class="ai-box">
                <h3>🤖 AI2 - <span id="ai2Count">0</span>张牌</h3>
                <div id="ai2Cards"></div>
            </div>
        </div>

        <div class="last-play-section">
            <h3>🃏 上一手</h3>
            <div class="last-cards-container" id="lastCards"></div>
            <div id="lastPlayInfo" style="margin-top: 10px;"></div>
        </div>

        <div class="bottom-section">
            <h2>🎴 底牌</h2>
            <div class="bottom-cards" id="bottomCards"></div>
        </div>

        <div class="player-section">
            <div class="player-header">
                👤 我的牌
                <span id="playerCount">0张</span>
            </div>
            <div class="cards-container" id="playerHand"></div>
        </div>
    </div>

    <script>
        function getCardImage(card) {
            if (card.value === 16) {
                return '小王.jpg';
            }
            if (card.value === 17) {
                return '大王.jpg';
            }

            const suitOrder = ['♠', '♥', '♣', '♦'];
            const valueOrder = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

            const valueIndex = valueOrder.indexOf(card.value);
            const suitIndex = suitOrder.indexOf(card.suit);

            
                return card.name + '.jpg';
            

        }

        function renderCards(containerId, cards, selectable = false) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            if (!cards || !Array.isArray(cards)) {
                return;
            }

            cards.forEach((card, index) => {
                const cardDiv = document.createElement('div');
                cardDiv.className = 'card';
                cardDiv.dataset.index = index;

                const img = document.createElement('img');
                img.src = 'img/' + getCardImage(card);
                img.alt = card.name;

                cardDiv.appendChild(img);

                if (selectable) {
                    cardDiv.onclick = function() {
                        this.classList.toggle('selected');
                    };
                }

                container.appendChild(cardDiv);
            });
        }

        function renderAICards(containerId, count, isThinking = false) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const cardDiv = document.createElement('div');
                cardDiv.className = 'ai-card-back' + (isThinking ? ' thinking' : '');
                container.appendChild(cardDiv);
            }
        }

        function renderLastCards(cards, playerName) {
            const container = document.getElementById('lastCards');
            const infoDiv = document.getElementById('lastPlayInfo');
            container.innerHTML = '';
            infoDiv.innerHTML = '';

            if (!cards || !Array.isArray(cards) || cards.length === 0) {
                infoDiv.innerHTML = '<span class="pass-text">无</span>';
                return;
            }

            cards.forEach(card => {
                const cardDiv = document.createElement('div');
                cardDiv.className = 'last-card';

                const img = document.createElement('img');
                img.src = 'img/' + getCardImage(card);
                img.alt = card.name;

                cardDiv.appendChild(img);
                container.appendChild(cardDiv);
            });

            infoDiv.innerHTML = '<span style="color: #ffd700; font-size: 16px;">' + playerName + '出的牌</span>';
        }

        function updateTurnInfo(currentPlayer) {
            const turnInfo = document.getElementById('turnInfo');
            let text = '';
            let bgColor = '';

            switch (currentPlayer) {
                case 0:
                    text = '👤 轮到你出牌';
                    bgColor = 'rgba(255,107,107,0.5)';
                    break;
                case 1:
                    text = '🤖 AI1 思考中...';
                    bgColor = 'rgba(100,200,100,0.5)';
                    break;
                case 2:
                    text = '🤖 AI2 思考中...';
                    bgColor = 'rgba(100,200,100,0.5)';
                    break;
                default:
                    text = '游戏结束';
                    bgColor = 'rgba(255,215,0,0.3)';
            }

            turnInfo.textContent = text;
            turnInfo.style.background = bgColor;

            renderAICards('ai1Cards', parseInt(document.getElementById('ai1Count').textContent), currentPlayer === 1);
            renderAICards('ai2Cards', parseInt(document.getElementById('ai2Count').textContent), currentPlayer === 2);
        }

        function getPlayerName(playerIndex) {
            return playerIndex === 0 ? '玩家' : (playerIndex === 1 ? 'AI1' : 'AI2');
        }

        async function initGame() {
            const response = await fetch('/api/init.php');
            const data = await response.json();

            if (data.code === 200) {
                await getState();
            } else {
                alert('初始化失败: ' + data.msg);
            }
        }

        async function getState() {
            const response = await fetch('/api/state.php');
            const data = await response.json();

            if (data.code === 200) {
                const gameData = data.data;

                document.getElementById('playerCount').textContent = gameData.player.handCards.length + '张';
                document.getElementById('ai1Count').textContent = gameData.ai1Count;
                document.getElementById('ai2Count').textContent = gameData.ai2Count;

                renderCards('playerHand', gameData.player.handCards, true);
                renderCards('bottomCards', gameData.bottom);
                renderAICards('ai1Cards', gameData.ai1Count);
                renderAICards('ai2Cards', gameData.ai2Count);

                if (gameData.lastCards) {
                    renderLastCards(gameData.lastCards, getPlayerName(gameData.lastPlayer));
                }

                updateTurnInfo(gameData.currentPlayer);

                if (gameData.gameOver) {
                    setTimeout(() => {
                        alert('🎉 游戏结束！' + getPlayerName(gameData.winner) + ' 获胜！');
                    }, 500);
                }
            }
        }

        async function playCards() {
            const selectedCards = document.querySelectorAll('.card.selected');
            if (selectedCards.length === 0) {
                alert('请选择要出的牌');
                return;
            }

            const indexes = [];
            selectedCards.forEach(card => {
                indexes.push(parseInt(card.dataset.index));
            });

            try {
                const response = await fetch('/api/play.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ indexes: indexes })
                });

                const data = await response.json();

                if (data.code === 200) {
                    await animateAIMoves(data.data);
                } else {
                    alert('出牌失败: ' + data.message);
                }
            } catch (error) {
                alert('出牌出错: ' + error.message);
            }
        }

        async function pass() {
            try {
                const response = await fetch('/api/pass.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.code === 200) {
                    await animateAIMoves(data.data);
                } else {
                    alert('操作失败: ' + data.message);
                }
            } catch (error) {
                alert('出错: ' + error.message);
            }
        }

        async function animateAIMoves(gameData) {
            if (!gameData || !gameData.player || !gameData.player.handCards) {
                alert('游戏数据错误');
                return;
            }

            document.getElementById('playerCount').textContent = gameData.player.handCards.length + '张';
            renderCards('playerHand', gameData.player.handCards, true);
            renderCards('bottomCards', gameData.bottom);

            const records = Array.isArray(gameData.gameRecord) ? gameData.gameRecord : [];
            if (records.length === 0) {
                updateState(gameData);
                return;
            }

            let lastPlayerIndex = -1;
            for (let i = records.length - 1; i >= 0; i--) {
                if (records[i].player === 0) {
                    lastPlayerIndex = i;
                    break;
                }
            }

            let aiStartIndex = lastPlayerIndex + 1;
            if (aiStartIndex >= records.length) {
                updateState(gameData);
                return;
            }

            for (let i = aiStartIndex; i < records.length; i++) {
                const record = records[i];
                const playerName = getPlayerName(record.player);

                if (record.player === 1) {
                    document.getElementById('ai1Count').textContent = gameData.ai1Count;
                    renderAICards('ai1Cards', gameData.ai1Count, true);
                } else if (record.player === 2) {
                    document.getElementById('ai2Count').textContent = gameData.ai2Count;
                    renderAICards('ai2Cards', gameData.ai2Count, true);
                }

                updateTurnInfo(record.player);

                await new Promise(resolve => setTimeout(resolve, 600));

                if (record.type === 'play') {
                    renderLastCards(record.cards, playerName);
                } else {
                    const container = document.getElementById('lastCards');
                    const infoDiv = document.getElementById('lastPlayInfo');
                    container.innerHTML = '';
                    infoDiv.innerHTML = '<span class="pass-text">' + playerName + ' 不要</span>';
                }

                await new Promise(resolve => setTimeout(resolve, 800));
            }

            updateState(gameData);
        }

        function updateState(gameData) {
            document.getElementById('ai1Count').textContent = gameData.ai1Count;
            document.getElementById('ai2Count').textContent = gameData.ai2Count;
            renderAICards('ai1Cards', gameData.ai1Count);
            renderAICards('ai2Cards', gameData.ai2Count);
            updateTurnInfo(gameData.currentPlayer);

            if (gameData.gameOver) {
                setTimeout(() => {
                    alert('🎉 游戏结束！' + getPlayerName(gameData.winner) + ' 获胜！');
                }, 500);
            }
        }
    </script>
</body>
</html>