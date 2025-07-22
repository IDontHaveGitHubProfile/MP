  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      font-family: 'Segoe UI';
      overflow: hidden;
    }

    body {
      position: relative;
    }

    .background-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('../public/assets/404.png') center/cover no-repeat;
      filter: blur(6px) brightness(0.5);
      z-index: -1;
    }

    #desktop404, #mobile404 {
      position: absolute;
      color: white;
    }

    #desktop404 {
      display: block;
      top: 10vh;
      left: 10vw;
      max-width: 600px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    #desktop404 h1 {
      font-size: clamp(2rem, 3vw, 3.5rem);
      margin-bottom: 1.5vh;
    }

    #desktop404 p {
      font-size: clamp(1rem, 1.2vw, 1.5rem);
      margin-bottom: 3vh;
      line-height: 1.5;
    }

    #mobile404 {
      display: none;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      padding: 4vw;
      width: 90%;
      background-color: rgba(0,0,0,0.5);
      border-radius: 2vw;
      text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }

    #mobile404 h1 {
      font-size: clamp(3rem, 15vw, 6rem);
      margin-bottom: 1vh;
    }

    #mobile404 h2 {
      font-size: clamp(1.2rem, 5vw, 2rem);
      margin-bottom: 2vh;
    }

    #mobile404 p {
      font-size: clamp(0.9rem, 4vw, 1.2rem);
      margin-bottom: 3vh;
    }

    .btn {
      display: inline-block;
      padding: clamp(8px, 2vw, 16px) clamp(16px, 4vw, 32px);
      background: #00AEEF;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      font-size: clamp(0.9rem, 3vw, 1.2rem);
    }

    .btn:hover {
      background: #007bb5;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
      #desktop404 {
        display: none;
      }
      #mobile404 {
        display: block;
      }
      .background-overlay {
        filter: blur(4px) brightness(0.4);
      }
    }

    @media (min-width: 2000px) {
      #desktop404 {
        left: 15vw;
        max-width: 800px;
      }
      #desktop404 h1 {
        font-size: 4rem;
      }
      #desktop404 p {
        font-size: 1.8rem;
      }
      .btn {
        padding: 20px 40px;
        font-size: 1.5rem;
      }
    }

    @media (max-width: 768px) and (orientation: landscape) {
      #mobile404 {
        width: 70%;
        padding: 2vw;
      }
      #mobile404 h1 {
        font-size: clamp(2rem, 8vw, 4rem);
        margin-bottom: 0.5vh;
      }
      #mobile404 h2 {
        font-size: clamp(1rem, 4vw, 1.5rem);
        margin-bottom: 1vh;
      }
      #mobile404 p {
        margin-bottom: 2vh;
      }
    }
  </style>
</head>
<body>
  <div class="background-overlay"></div>

  <div id="desktop404">
    <h1>404 - Страница не найдена</h1>
    <p>К сожалению, запрашиваемая вами страница не существует или была удалена.</p>
    <a href="index.php?page=home" class="btn">Вернуться на главную</a>
  </div>

  <div id="mobile404">
    <h1>404</h1>
    <h2>Страница не найдена</h2>
    <p>Запрашиваемая вами страница не существует.</p>
    <a href="index.php?page=home" class="btn">Вернуться на главную</a>
  </div>
</body>
</html>