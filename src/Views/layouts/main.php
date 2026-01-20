<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?? 'Analizador de Componentes PC'; ?>
    </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        h1 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 2.5em;
        }

        h2 {
            color: #764ba2;
            margin: 30px 0 15px;
            font-size: 1.8em;
        }

        h3 {
            color: #667eea;
            margin: 20px 0 10px;
        }

        .message {
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            font-size: 1.1em;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .nav {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }

        .nav a {
            display: inline-block;
            margin-right: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav a:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="nav">
            <a href="/">🏠 Inicio</a>
            <a href="/search">🔍 Buscar Componentes</a>
            <a href="/data?key=12345">📊 Importar Datos</a>
        </nav>

        <?php echo $content; ?>

        <footer>
            <p>&copy;
                <?php echo date('Y'); ?> - Analizador de Componentes PC con IA
            </p>
        </footer>
    </div>
</body>

</html>